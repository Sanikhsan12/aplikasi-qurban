<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Pelaksanaan;
use App\Models\KetersediaanHewan;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    /**
     * Create a new order with participants.
     */
    public function createOrder(array $data, int $userId): Order
    {
        DB::beginTransaction();

        try {
            $pelaksanaanAktif = Pelaksanaan::where('status', 'Active')->first();

            if (!$pelaksanaanAktif) {
                throw new Exception('Tidak ada pelaksanaan aktif.');
            }

            $orderData = [
                'user_id' => $userId,
                'tipe_pendaftaran' => $data['tipe_pendaftaran'],
                'total_hewan' => $data['total_hewan'],
                'pelaksanaan_id' => $pelaksanaanAktif->id,
                'status' => $data['tipe_pendaftaran'] === 'transfer'
                    ? 'menunggu verifikasi'
                    : 'disetujui',
            ];

            $jenisHewan = null;

            if ($data['tipe_pendaftaran'] === 'transfer') {
                $hewan = KetersediaanHewan::findOrFail($data['ketersediaan_hewan_id']);

                if ($hewan->jumlah < $data['total_hewan']) {
                    throw new Exception('Stok hewan tidak mencukupi.');
                }

                $jenisHewan = $hewan->jenis_hewan;

                $orderData = array_merge($orderData, [
                    'ketersediaan_hewan_id' => $hewan->id,
                    'jenis_hewan' => $jenisHewan,
                    'berat_hewan' => $hewan->bobot,
                    'perkiraan_daging' => $hewan->bobot * 0.4,
                    'total_harga' => $hewan->harga,
                    'bank_id' => null,
                ]);

                $hewan->decrement('jumlah');
            } else {
                $jenisHewan = $data['jenis_hewan'];

                $orderData = array_merge($orderData, [
                    'ketersediaan_hewan_id' => null,
                    'bank_id' => null,
                    'bukti_pembayaran' => null,
                    'jenis_hewan' => $jenisHewan,
                    'berat_hewan' => $data['berat_kirim'],
                    'perkiraan_daging' => $data['berat_kirim'] * 0.4,
                    'total_harga' => 0,
                ]);
            }

            $isSapi = strcasecmp($jenisHewan, 'Sapi') === 0;

            if ($isSapi) {
                for ($i = 2; $i <= 7; $i++) {
                    $namaField = "peserta_{$i}";
                    if (empty($data[$namaField])) {
                        throw new Exception("Nama peserta {$i} wajib diisi untuk hewan Sapi.");
                    }
                }
            }

            $order = Order::create($orderData);

            $order->peserta()->create([
                'nama_peserta' => auth()->user()->name ?? 'Peserta',
                'is_buyer' => true,
            ]);

            if ($isSapi) {
                for ($i = 2; $i <= 7; $i++) {
                    $order->peserta()->create([
                        'nama_peserta' => $data["peserta_{$i}"],
                        'is_buyer' => false,
                    ]);
                }
            }

            DB::commit();
            return $order;
        } catch (Exception $e) {
            DB::rollBack();
            \Log::error('Store Order Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Verify an order.
     */
    public function verifyOrder(Order $order, ?string $alasanPenolakan, int $userId): Order
    {
        DB::beginTransaction();
        try {
            $order->update([
                'status' => 'disetujui',
                'alasan_penolakan' => $alasanPenolakan,
                'verified_at' => now(),
                'verified_by' => $userId,
                'rejected_at' => null,
                'rejected_by' => null,
            ]);

            DB::commit();
            return $order;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reject an order.
     */
    public function rejectOrder(Order $order, string $alasanPenolakan, int $userId): Order
    {
        DB::beginTransaction();
        try {
            if ($order->tipe_pendaftaran === 'transfer' && $order->ketersediaan_hewan_id) {
                $hewan = KetersediaanHewan::find($order->ketersediaan_hewan_id);
                if ($hewan) {
                    $hewan->increment('jumlah', $order->total_hewan);

                    if ($hewan->status == 'habis') {
                        $hewan->update(['status' => 'tersedia']);
                    }
                }
            }

            $order->update([
                'status' => 'ditolak',
                'alasan_penolakan' => $alasanPenolakan,
                'rejected_at' => now(),
                'rejected_by' => $userId,
                'verified_at' => null,
                'verified_by' => null,
            ]);

            DB::commit();
            return $order;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
