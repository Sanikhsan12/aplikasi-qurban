<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use Exception;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function show(Order $order)
    {
        if (auth()->id() !== $order->user_id && !auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Anda tidak diizinkan mengakses halaman ini.');
        }

        if ($order->status !== 'menunggu verifikasi') {
            return redirect()->back()->with('error', 'Order ini tidak dapat dibayar.');
        }

        return view('payment.show', [
            'order' => $order,
            'clientKey' => config('midtrans.client_key'),
        ]);
    }

    public function getSnapToken(Order $order)
    {
        if (auth()->id() !== $order->user_id && !auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $data = $this->paymentService->getSnapToken($order);
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Gagal membuat token pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        try {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');

            $notif = new \Midtrans\Notification();
            $orderId = explode('-', $notif->order_id)[1];
            $order = Order::findOrFail($orderId);

            $transaction = \Midtrans\Transaction::status($notif->order_id);
            $this->paymentService->handlePaymentStatus($order, $transaction);

            return response()->json(['status' => 'ok']);
        } catch (Exception $e) {
            \Log::error('MidTrans callback error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function finish(Request $request)
    {
        if ($request->has('order_id')) {
            $order = Order::find($request->order_id);
            if ($order && $order->bukti_pembayaran) {
                try {
                    $this->paymentService->verifyPayment($order);
                } catch (Exception $e) {
                    \Log::error('Manual verify error: ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('peserta.dashboard')
            ->with('success', 'Transaksi Anda telah diproses. Cek status terbaru di bawah.');
    }

    public function error(Request $request)
    {
        return redirect()->route('peserta.dashboard')
            ->with('error', 'Pembayaran dibatalkan atau gagal.');
    }

    public function verify(Order $order)
    {
        try {
            if (auth()->id() !== $order->user_id && !auth()->user()->isAdmin()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $result = $this->paymentService->verifyPayment($order);

            return response()->json($result);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
