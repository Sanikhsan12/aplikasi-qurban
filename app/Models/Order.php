<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    
    protected $fillable = [
        'user_id',
        'pelaksanaan_id',
        'ketersediaan_hewan_id',
        'bank_id',
        'tipe_pendaftaran',
        'jenis_hewan',
        'berat_hewan',
        'total_hewan',
        'perkiraan_daging',
        'total_harga',
        'bukti_pembayaran',
        'status',
        'alasan_penolakan',
    ];



    protected $casts = [
        'total_harga' => 'decimal:2',
        'berat_hewan' => 'decimal:2',
        'perkiraan_daging' => 'decimal:2',
        'total_hewan' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bank()
    {
        return $this->belongsTo(BankPenerima::class);
    }

    public function ketersediaanHewan()
    {
        return $this->belongsTo(KetersediaanHewan::class);
    }

    public function danaDKM()
    {
        return $this->hasOne(DanaDKM::class);
    }


    // Accessors
    public function getTotalHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getBeratHewanFormattedAttribute()
    {
        return number_format($this->berat_hewan, 1, ',', '.') . ' kg';
    }

    public function getPerkiraanDagingFormattedAttribute()
    {
        return number_format($this->perkiraan_daging, 1, ',', '.') . ' kg';
    }

    public function getStatusFormattedAttribute()
    {
        $statuses = [
            'pending' => 'Menunggu',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getBuktiPembayaranUrlAttribute()
    {
        if ($this->bukti_pembayaran && file_exists(storage_path('app/public/bukti_pembayaran/' . $this->bukti_pembayaran))) {
            return asset('storage/bukti_pembayaran/' . $this->bukti_pembayaran);
        }

        return null;
    }

    /**
     * Relasi ke model Penyembelihan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function penyembelihan()
    {
        return $this->hasOne(Penyembelihan::class);
    }

    /**
     * Cek apakah order sudah memiliki penyembelihan.
     *
     * @return bool
     */
    public function hasPenyembelihan(): bool
    {
        return !is_null($this->penyembelihan);
    }

    /**
     * Cek apakah order sudah tersembelih.
     *
     * @return bool
     */
    public function isTersembelih(): bool
    {
        return $this->hasPenyembelihan() && $this->penyembelihan->isTersembelih();
    }

    /**
     * Cek apakah order sudah terdistribusi.
     *
     * @return bool
     */
    public function isTerdistribusi(): bool
    {
        return $this->hasPenyembelihan() && $this->penyembelihan->isTerdistribusi();
    }
    
    /**
     * Check if order can be paid
     *
     * @return bool
     */
    public function canBePaid(): bool
    {
        return in_array($this->status, ['pending', 'menunggu_verifikasi']);
    }

    /**
     * Check if order is paid
     *
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->status === 'disetujui';
    }

    /**
     * Check if order is rejected
     *
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->status === 'ditolak';
    }

    /**
     * Get payment status label
     *
     * @return string
     */
    public function getPaymentStatusLabel(): string
    {
        $labels = [
            'pending' => 'Menunggu Pembayaran',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'disetujui' => 'Pembayaran Diterima',
            'ditolak' => 'Pembayaran Ditolak',
        ];

        return $labels[$this->status] ?? $this->status;
    }
}
