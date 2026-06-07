<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanaOperasional extends Model
{
    protected $table = 'dana_operasional';

    protected $fillable = [
        'keperluan',
        'jumlah_pengeluaran',
        'keterangan',
        'id_dkm',
        'id_user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function danaDKM()
    {
        return $this->belongsTo(DanaDKM::class, 'id_dkm');
    }


    /**
     * Accessor untuk format jumlah pengeluaran.
     *
     * @return string
     */
    public function getJumlahPengeluaranFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah_pengeluaran, 0, ',', '.');
    }

    /**
     * Scope untuk data yang dibuat oleh user tertentu.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('id_user', $userId);
    }

    /**
     * Scope untuk data dengan dana DKM tertentu.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $danaDkmId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDanaDkm($query, $danaDkmId)
    {
        return $query->where('id_dkm', $danaDkmId);
    }

    /**
     * Scope untuk pencarian.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('keperluan', 'like', '%' . $search . '%')
                    ->orWhere('keterangan', 'like', '%' . $search . '%');
    }
}
