<?php

namespace App\Observers;

use App\Models\DanaDKM;
use App\Models\Order;
use App\Models\Penyembelihan;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order)
    {
        /**
         * 1. Pastikan status benar-benar berubah
         * 2. Pastikan status menjadi 'disetujui'
         */
        if (
            $order->wasChanged('status') &&
            $order->status === 'disetujui'
        ) {
            /**
             * 3. Cegah duplikasi penyembelihan
             */
            if ($order->penyembelihan()->exists()) {
                return;
            }

            /**
             * 4. Insert ke tabel penyembelihan
             */
            Penyembelihan::create([
                'order_id'       => $order->id,
                'pelaksanaan_id' => $this->getPelaksanaanAktif(),
                'status'         => 'menunggu penyembelihan',
            ]);

            /**
             * 4. Insert ke tabel dana dkm
             */

            if ($order->tipe_pendaftaran === 'transfer') {
                DanaDKM::create([
                    'order_id'       => $order->id,
                    'sumber_dana'    => 'transfer peserta kurban',
                    'jumlah_dana'    => $order->total_harga,
                    'keterangan'     => 'pembayaran kurban peserta ' . $order->user->name,
                ]);
            }
        }
    }

    protected function getPelaksanaanAktif(): ?int
    {
        return \App\Models\Pelaksanaan::whereDate('penyembelihan', '>=', now()->toDateString())
            ->orderBy('penyembelihan', 'asc')
            ->value('id');
    }


    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
