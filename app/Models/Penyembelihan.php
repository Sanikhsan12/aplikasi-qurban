<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penyembelihan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'penyembelihans';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'pelaksanaan_id',
        'status',
        'dokumentasi_penyembelihan',
    ];

    /**
     * Kolom yang harus di-cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Constants untuk status penyembelihan.
     */
    const STATUS_MENUNGGU = 'menunggu penyembelihan';
    const STATUS_TERSEMBELIH = 'tersembelih';
    const STATUS_TERDISTRIBUSI = 'terdistribusi';

    /**
     * Dapatkan opsi status yang valid.
     *
     * @return array
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_MENUNGGU => 'Menunggu Penyembelihan',
            self::STATUS_TERSEMBELIH => 'Tersembelih',
            self::STATUS_TERDISTRIBUSI => 'Terdistribusi',
        ];
    }

    /**
     * Dapatkan warna badge berdasarkan status.
     *
     * @param string $status
     * @return string
     */
    public static function getStatusColor($status)
    {
        $colors = [
            self::STATUS_MENUNGGU => 'warning',
            self::STATUS_TERSEMBELIH => 'info',
            self::STATUS_TERDISTRIBUSI => 'success',
        ];

        return $colors[$status] ?? 'secondary';
    }

    /**
     * Relasi ke model Order.
     *
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi ke model Pelaksanaan.
     *
     * @return BelongsTo
     */
    public function pelaksanaan(): BelongsTo
    {
        return $this->belongsTo(Pelaksanaan::class);
    }

    /**
     * Accessor untuk mendapatkan label status.
     *
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    /**
     * Accessor untuk mendapatkan warna status.
     *
     * @return string
     */
    public function getStatusColorAttribute(): string
    {
        return self::getStatusColor($this->status);
    }

    /**
     * Accessor untuk mendapatkan URL dokumentasi.
     *
     * @return string|null
     */
    public function getDokumentasiUrlAttribute(): ?string
    {
        if (!$this->dokumentasi_penyembelihan) {
            return null;
        }

        return Storage::url($this->dokumentasi_penyembelihan);
    }

    /**
     * Scope untuk mendapatkan penyembelihan berdasarkan status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk mendapatkan penyembelihan yang menunggu.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMenunggu($query)
    {
        return $query->where('status', self::STATUS_MENUNGGU);
    }

    /**
     * Scope untuk mendapatkan penyembelihan yang tersembelih.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTersembelih($query)
    {
        return $query->where('status', self::STATUS_TERSEMBELIH);
    }

    /**
     * Scope untuk mendapatkan penyembelihan yang terdistribusi.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTerdistribusi($query)
    {
        return $query->where('status', self::STATUS_TERDISTRIBUSI);
    }

    /**
     * Cek apakah status menunggu penyembelihan.
     *
     * @return bool
     */
    public function isMenunggu(): bool
    {
        return $this->status === self::STATUS_MENUNGGU;
    }

    /**
     * Cek apakah status tersembelih.
     *
     * @return bool
     */
    public function isTersembelih(): bool
    {
        return $this->status === self::STATUS_TERSEMBELIH;
    }

    /**
     * Cek apakah status terdistribusi.
     *
     * @return bool
     */
    public function isTerdistribusi(): bool
    {
        return $this->status === self::STATUS_TERDISTRIBUSI;
    }
}