<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class KetersediaanHewan extends Model
{
    use HasFactory;

    protected $table = 'ketersediaan_hewan';

    protected $fillable = [
        'jenis_hewan',
        'bobot',
        'harga',
        'jumlah',
        'foto'
    ];

    protected $casts = [
        'bobot' => 'decimal:2',
        'harga' => 'decimal:2',
        'jumlah' => 'integer'
    ];

    // Accessor untuk format harga
    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Accessor untuk format bobot
    public function getBobotFormattedAttribute()
    {
        return number_format($this->bobot, 1, ',', '.') . ' kg';
    }

    // Accessor untuk total harga
    public function getTotalHargaAttribute()
    {
        return $this->harga * $this->jumlah;
    }

    public function getTotalHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    // Accessor untuk URL foto
    public function getFotoUrlAttribute()
    {
        // Jika tidak ada foto
        if (!$this->foto) {
            return asset('images/default-animal.jpg');
        }

        // Jika foto ada di storage (setelah storage:link)
        $storagePath = 'storage/hewan/' . $this->foto;
        $fullPath = public_path($storagePath);

        if (file_exists($fullPath)) {
            return asset($storagePath);
        }

        // Jika foto ada di storage/app/public (belum di-link)
        $appStoragePath = storage_path('app/public/hewan/' . $this->foto);
        if (file_exists($appStoragePath)) {
            // Periksa apakah storage link sudah ada
            if (is_link(public_path('storage'))) {
                return asset('storage/hewan/' . $this->foto);
            }
        }

        // Jika foto ada di public/uploads
        $uploadPath = 'uploads/hewan/' . $this->foto;
        $fullUploadPath = public_path($uploadPath);
        if (file_exists($fullUploadPath)) {
            return asset($uploadPath);
        }

        // Default fallback
        return asset('images/default-animal.jpg');
    }

    // Scope untuk filter
    public function scopeFilter($query, $filters)
    {
        if (isset($filters['jenis_hewan']) && $filters['jenis_hewan']) {
            $query->where('jenis_hewan', $filters['jenis_hewan']);
        }

        if (isset($filters['min_bobot']) && $filters['min_bobot']) {
            $query->where('bobot', '>=', $filters['min_bobot']);
        }

        if (isset($filters['max_bobot']) && $filters['max_bobot']) {
            $query->where('bobot', '<=', $filters['max_bobot']);
        }

        if (isset($filters['min_harga']) && $filters['min_harga']) {
            $query->where('harga', '>=', $filters['min_harga']);
        }

        if (isset($filters['max_harga']) && $filters['max_harga']) {
            $query->where('harga', '<=', $filters['max_harga']);
        }

        return $query;
    }
}
