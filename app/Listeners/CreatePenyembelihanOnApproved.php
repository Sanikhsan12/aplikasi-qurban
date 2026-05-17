<?php
// app/Listeners/CreatePenyembelihanOnApproved.php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use App\Models\Penyembelihan;
use App\Models\Pelaksanaan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CreatePenyembelihanOnApproved
{
    public function handle(OrderStatusUpdated $event)
    {
        Log::info('===== CreatePenyembelihanOnApproved START =====');
        Log::info('Order Status Changed:', [
            'order_id' => $event->order->id,
            'from' => $event->oldStatus,
            'to' => $event->newStatus
        ]);
        
        // Cek jika status berubah menjadi 'disetujui'
        if ($event->newStatus === 'disetujui' && $event->oldStatus !== 'disetujui') {
            
            // Cek apakah sudah ada data penyembelihan untuk order ini
            $existing = Penyembelihan::where('order_id', $event->order->id)->first();
            
            if (!$existing) {
                Log::info('No existing penyembelihan found, creating new one...');
                
                // 1. AMBIL PELAKSANAAN YANG SESUAI
                // INI YANG SALAH SEBELUMNYA: where('Penyembelihan', ...)
                // HARUSNYA: where pada kolom di tabel pelaksanaans
                
                $pelaksanaan = $this->getPelaksanaanSesuai($event->order);
                
                Log::info('Selected pelaksanaan:', [
                    'pelaksanaan_id' => $pelaksanaan ? $pelaksanaan->id : 'NULL',
                    'pelaksanaan_data' => $pelaksanaan ? $pelaksanaan->toArray() : 'No pelaksanaan found'
                ]);
                
                // 2. BUAT DATA PENYEMBELIHAN BARU
                try {
                    $penyembelihan = Penyembelihan::create([
                        'order_id' => $event->order->id,
                        'pelaksanaan_id' => $pelaksanaan ? $pelaksanaan->id : null,
                        'status' => 'menunggu penyembelihan',
                        'dokumentasi penyembelihan' => null,
                    ]);
                    
                    Log::info('Penyembelihan created successfully!', [
                        'id' => $penyembelihan->id,
                        'order_id' => $penyembelihan->order_id,
                        'pelaksanaan_id' => $penyembelihan->pelaksanaan_id
                    ]);
                    
                } catch (\Exception $e) {
                    Log::error('Failed to create penyembelihan:', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
                
            } else {
                Log::info('Penyembelihan already exists:', [
                    'penyembelihan_id' => $existing->id,
                    'order_id' => $existing->order_id
                ]);
            }
        } else {
            Log::info('Skipping: Status not changed to "disetujui"');
        }
        
        Log::info('===== CreatePenyembelihanOnApproved END =====');
    }
    
    private function getPelaksanaanSesuai($order)
    {
        Log::info('Getting suitable pelaksanaan for order:', [
            'order_id' => $order->id,
            'total_hewan' => $order->total_hewan
        ]);
        
        // KOREKSI: JANGAN GUNAKAN 'Penyembelihan' 
        // Itu nama tabel penyembelihan, bukan kolom di pelaksanaan
        
        // Cek dulu struktur tabel pelaksanaans
        $columns = DB::getSchemaBuilder()->getColumnListing('pelaksanaans');
        Log::info('Columns in pelaksanaans table:', $columns);
        
        // ASUSMSI: Kolom tanggal mungkin bernama:
        // - tanggal_penyembelihan
        // - tanggal
        // - waktu_penyembelihan
        // - tgl_penyembelihan
        
        $query = Pelaksanaan::query();
        
        // Pilih salah satu yang sesuai dengan struktur Anda:
        
        // Opsi 1: Jika ada kolom tanggal spesifik
        if (in_array('Penyembelihan', $columns)) {
            $query->where('Penyembelihan', '>=', now())
                  ->orderBy('Penyembelihan', 'asc');
        }
        // Opsi 2: Jika kolomnya hanya 'tanggal'
        elseif (in_array('tanggal', $columns)) {
            $query->where('tanggal', '>=', now())
                  ->orderBy('tanggal', 'asc');
        }
        // Opsi 3: Jika tidak ada kolom tanggal, ambil yang terbaru
        else {
            Log::warning('No date column found in pelaksanaans, using latest');
            $query->orderBy('created_at', 'desc');
        }
        
        // // Tambahkan filter status jika ada
        // if (in_array('status', $columns)) {
        //     $query->where('status', 'terjadwal'); // atau 'aktif' sesuai kebutuhan
        // }
        
        $pelaksanaan = $query->first();
        
        if (!$pelaksanaan) {
            Log::warning('No suitable pelaksanaan found!');
            
            // Buat pelaksanaan default jika tidak ada
            $pelaksanaan = $this->createDefaultPelaksanaan();
        }
        
        return $pelaksanaan;
    }
    
    private function createDefaultPelaksanaan()
    {
        try {
            Log::info('Creating default pelaksanaan...');
            
            $pelaksanaan = Pelaksanaan::create([
                'nama' => 'Penyembelihan ' . date('d-m-Y'),
                'tanggal' => now()->addDays(1), // besok
                'lokasi' => 'Lokasi Utama',
                'kapasitas' => 100,
                'status' => 'terjadwal',
                'keterangan' => 'Dibuat otomatis oleh system'
            ]);
            
            Log::info('Default pelaksanaan created:', ['id' => $pelaksanaan->id]);
            return $pelaksanaan;
            
        } catch (\Exception $e) {
            Log::error('Failed to create default pelaksanaan:', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}