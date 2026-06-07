<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction;
use Exception;

class PaymentService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Get Snap token for payment.
     */
    public function getSnapToken(Order $order): array
    {
        $transactionDetails = [
            'order_id' => 'ORDER-' . $order->id . '-' . time(),
            'gross_amount' => (int) $order->total_harga,
        ];

        $itemDetails = [
            [
                'id' => $order->id,
                'price' => (int) $order->total_harga,
                'quantity' => 1,
                'name' => 'Peserta Kurban - ' . $order->jenis_hewan . ' (' . $order->total_hewan . ' ekor)',
            ]
        ];

        $customerDetails = [
            'first_name' => $order->user->name ?? 'Customer',
            'email' => $order->user->email ?? 'customer@example.com',
            'phone' => $order->user->phone ?? '',
        ];

        $transaction = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
            'enable_redirect_url' => true,
            'redirect_url' => route('payment.callback'),
            'finish_redirect_url' => route('payment.finish'),
            'error_redirect_url' => route('payment.error'),
        ];

        $snapToken = Snap::getSnapToken($transaction);

        $order->update([
            'bukti_pembayaran' => json_encode(['order_id' => $transactionDetails['order_id']])
        ]);

        return [
            'snap_token' => $snapToken,
            'order_id' => $transactionDetails['order_id'],
        ];
    }

    /**
     * Handle payment status based on Midtrans transaction data.
     */
    public function handlePaymentStatus(Order $order, $transaction)
    {
        $transactionStatus = $transaction->transaction_status;
        $fraudStatus = $transaction->fraud_status ?? null;

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'challenge') {
                $order->update([
                    'status' => 'menunggu verifikasi',
                    'bukti_pembayaran' => json_encode($transaction),
                ]);
            } else if ($fraudStatus === 'accept') {
                $order->update([
                    'status' => 'disetujui',
                    'bukti_pembayaran' => json_encode($transaction),
                ]);
            }
        } else if ($transactionStatus === 'settlement') {
            $order->update([
                'status' => 'disetujui',
                'bukti_pembayaran' => json_encode($transaction),
            ]);
            
            event(new \App\Events\OrderApproved($order));
        } else if ($transactionStatus === 'pending') {
            $order->update([
                'status' => 'menunggu verifikasi',
                'bukti_pembayaran' => json_encode($transaction),
            ]);
        } else if ($transactionStatus === 'deny') {
            $order->update([
                'status' => 'ditolak',
                'alasan_penolakan' => 'Pembayaran ditolak oleh sistem MidTrans',
                'bukti_pembayaran' => json_encode($transaction),
            ]);
        } else if ($transactionStatus === 'cancel' || $transactionStatus === 'expire') {
            $order->update([
                'status' => 'menunggu verifikasi',
                'bukti_pembayaran' => null,
            ]);
        }
    }

    /**
     * Verify payment explicitly via Midtrans.
     */
    public function verifyPayment(Order $order): array
    {
        $transactionDetails = json_decode($order->bukti_pembayaran, true);
        
        if ($transactionDetails && isset($transactionDetails['order_id'])) {
            $transaction = Transaction::status($transactionDetails['order_id']);
            $this->handlePaymentStatus($order, $transaction);
            $order->refresh();

            return [
                'status' => $order->status,
                'message' => $order->getStatusFormattedAttribute ?? 'Status updated',
            ];
        }

        throw new Exception('No transaction found');
    }
}
