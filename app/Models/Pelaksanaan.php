<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelaksanaan extends Model
{
    protected $table = 'pelaksanaans';

    protected $fillable = [
        'Tanggal_Pendaftaran',
        'Tanggal_Penutupan',
        'Ketuplak',
        'Lokasi',
        'Penyembelihan',
        'Status',
    ];

    protected $casts = [
        'Tanggal_Pendaftaran' => 'date',
        'Tanggal_Penutupan' => 'date',
        'Penyembelihan' => 'date',
    ];

    /**
     * Format tanggal untuk ditampilkan
     */
    public function getTanggalPendaftaranFormattedAttribute()
    {
        return $this->Tanggal_Pendaftaran
            ? $this->Tanggal_Pendaftaran->format('d F Y')
            : '-';
    }

    public function getTanggalPenutupanFormattedAttribute()
    {
        return $this->Tanggal_Penutupan
            ? $this->Tanggal_Penutupan->format('d F Y')
            : '-';
    }

    public function getPenyembelihanFormattedAttribute()
    {
        return $this->Penyembelihan
            ? $this->Penyembelihan->format('d F Y')
            : '-';
    }

    // /**
    //  * Cek status apakah pendaftaran masih aktif
    //  */
    public function getStatusAttribute()
    {
        $today = now();
        $penutupan = $this->Tanggal_Penutupan;

        if (!$penutupan) {
            return 'unknown';
        }

        return $today->gt($penutupan) ? 'Closed' : 'Active';
    }

    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            'Active' => 'Active',
            'Closed' => 'Closed',
            default => 'Tidak Diketahui',
        };
    }

    public function penyembelihans()
    {
        return $this->hasMany(Penyembelihan::class);
    }

    public function distribusi()
    {
        return $this->hasMany(DistribusiDaging::class);
    }
}
