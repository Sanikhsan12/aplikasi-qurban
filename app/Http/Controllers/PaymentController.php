<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display payment form for an order
     */
    public function show(Order $order)
    {
        // Check if user is authorized to pay this order
        if (auth()->id() !== $order->user_id && !auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Anda tidak diizinkan mengakses halaman ini.');
        }

        // Check if order is pending
        if ($order->status !== 'pending' && $order->status !== 'menunggu_verifikasi') {
            return redirect()->back()->with('error', 'Order ini tidak dapat dibayar.');
        }

        return view('payment.show', [
            'order' => $order,
            'clientKey' => config('midtrans.client_key'),
        ]);
    }

    /**
     * Get Snap token for payment
     */
    public function getSnapToken(Order $order)
    {
        // Check authorization
        if (auth()->id() !== $order->user_id && !auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Set MidTrans configuration
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

            // Prepare transaction details
            $transactionDetails = [
                'order_id' => 'ORDER-' . $order->id . '-' . time(),
                'gross_amount' => (int) $order->total_harga,
            ];

            // Prepare item details
            $itemDetails = [
                [
                    'id' => $order->id,
                    'price' => (int) $order->total_harga,
                    'quantity' => 1,
                    'name' => 'Peserta Kurban - ' . $order->jenis_hewan . ' (' . $order->total_hewan . ' ekor)',
                ]
            ];

            // Prepare customer details
            $customerDetails = [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->user->phone ?? '',
            ];

            // Prepare payment params
            $transaction = [
                'transaction_details' => $transactionDetails,
                'item_details' => $itemDetails,
                'customer_details' => $customerDetails,
                'enable_redirect_url' => true,
                'redirect_url' => route('payment.callback'),
                'finish_redirect_url' => route('payment.finish'),
                'error_redirect_url' => route('payment.error'),
            ];

            // Get Snap token
            $snapToken = \Midtrans\Snap::getSnapToken($transaction);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $transactionDetails['order_id'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal membuat token pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle payment callback from MidTrans
     */
    public function callback(Request $request)
    {
        try {
            // Set MidTrans configuration
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');

            // Get notification from MidTrans
            $notif = new \Midtrans\Notification();

            // Get order ID from notification
            $orderId = explode('-', $notif->order_id)[1];
            $order = Order::findOrFail($orderId);

            // Get transaction status from MidTrans
            $transaction = \Midtrans\Transaction::status($notif->order_id);

            // Handle payment status
            $this->handlePaymentStatus($order, $transaction);

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            \Log::error('MidTrans callback error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle payment finish
     */
    public function finish(Request $request)
    {
        return redirect()->route('peserta.dashboard')
            ->with('success', 'Terima kasih! Pembayaran Anda sedang diproses.');
    }

    /**
     * Handle payment error
     */
    public function error(Request $request)
    {
        return redirect()->route('peserta.dashboard')
            ->with('error', 'Pembayaran dibatalkan atau gagal.');
    }

    /**
     * Handle payment status update
     */
    private function handlePaymentStatus($order, $transaction)
    {
        $transactionStatus = $transaction->transaction_status;
        $paymentType = $transaction->payment_type ?? null;

        if ($transactionStatus === 'capture') {
            if ($transaction->fraud_status === 'challenge') {
                // Still waiting for fraud verification
                $order->update([
                    'status' => 'menunggu_verifikasi',
                    'bukti_pembayaran' => json_encode($transaction),
                ]);
            } else if ($transaction->fraud_status === 'accept') {
                // Payment accepted
                $order->update([
                    'status' => 'disetujui',
                    'bukti_pembayaran' => json_encode($transaction),
                ]);
            }
        } else if ($transactionStatus === 'settlement') {
            // Payment completed
            $order->update([
                'status' => 'disetujui',
                'bukti_pembayaran' => json_encode($transaction),
            ]);
            
            // Fire event for order approved (if listener exists)
            event(new \App\Events\OrderApproved($order));
        } else if ($transactionStatus === 'pending') {
            // Payment pending
            $order->update([
                'status' => 'menunggu_verifikasi',
                'bukti_pembayaran' => json_encode($transaction),
            ]);
        } else if ($transactionStatus === 'deny') {
            // Payment denied
            $order->update([
                'status' => 'ditolak',
                'alasan_penolakan' => 'Pembayaran ditolak oleh sistem MidTrans',
                'bukti_pembayaran' => json_encode($transaction),
            ]);
        } else if ($transactionStatus === 'cancel' || $transactionStatus === 'expire') {
            // Payment canceled or expired
            $order->update([
                'status' => 'pending',
                'bukti_pembayaran' => null,
            ]);
        }
    }

    /**
     * Verify payment status
     */
    public function verify(Order $order)
    {
        try {
            // Check authorization
            if (auth()->id() !== $order->user_id && !auth()->user()->isAdmin()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Set MidTrans configuration
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');

            // Get latest transaction status
            $transactionDetails = json_decode($order->bukti_pembayaran, true);
            
            if ($transactionDetails && isset($transactionDetails['order_id'])) {
                $transaction = \Midtrans\Transaction::status($transactionDetails['order_id']);
                
                // Update order status based on transaction
                $this->handlePaymentStatus($order, $transaction);
                
                // Refresh order
                $order->refresh();

                return response()->json([
                    'status' => $order->status,
                    'message' => $order->getStatusFormattedAttribute(),
                ]);
            }

            return response()->json(['error' => 'No transaction found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
