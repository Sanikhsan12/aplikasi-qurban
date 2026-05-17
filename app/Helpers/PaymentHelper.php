<?php

namespace App\Helpers;

use App\Models\Order;

class PaymentHelper
{
    /**
     * Format currency to Indonesian Rupiah
     */
    public static function formatRupiah($amount)
    {
        return 'Rp' . number_format((int) $amount, 0, ',', '.');
    }

    /**
     * Generate unique order ID for MidTrans
     */
    public static function generateOrderId(Order $order)
    {
        return 'ORDER-' . $order->id . '-' . time();
    }

    /**
     * Check if order can be paid
     */
    public static function canBePaid(Order $order)
    {
        return in_array($order->status, ['pending', 'menunggu_verifikasi']);
    }

    /**
     * Get payment status label
     */
    public static function getPaymentStatusLabel($status)
    {
        $labels = [
            'pending' => 'Menunggu Pembayaran',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'disetujui' => 'Pembayaran Diterima',
            'ditolak' => 'Pembayaran Ditolak',
        ];

        return $labels[$status] ?? $status;
    }

    /**
     * Get payment status color
     */
    public static function getPaymentStatusColor($status)
    {
        $colors = [
            'pending' => 'warning',
            'menunggu_verifikasi' => 'info',
            'disetujui' => 'success',
            'ditolak' => 'danger',
        ];

        return $colors[$status] ?? 'secondary';
    }

    /**
     * Get payment status icon
     */
    public static function getPaymentStatusIcon($status)
    {
        $icons = [
            'pending' => 'fa-clock',
            'menunggu_verifikasi' => 'fa-hourglass-half',
            'disetujui' => 'fa-check-circle',
            'ditolak' => 'fa-times-circle',
        ];

        return $icons[$status] ?? 'fa-question-circle';
    }
}
