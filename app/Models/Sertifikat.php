<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sertifikat extends Model
{
    protected $fillable = [
        'order_id',
        'penyembelihan_id',
        'order_peserta_id',
        'nomor_sertifikat',
        'nama_peserta',
        'jenis_hewan',
        'tanggal_penyembelihan',
        'file_path',
    ];

    protected $casts = [
        'tanggal_penyembelihan' => 'date',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function penyembelihan(): BelongsTo
    {
        return $this->belongsTo(Penyembelihan::class);
    }

    public function orderPeserta(): BelongsTo
    {
        return $this->belongsTo(OrderPeserta::class);
    }
}
