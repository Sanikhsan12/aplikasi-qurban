<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPeserta extends Model
{
    protected $table = 'order_peserta';

    protected $fillable = [
        'order_id',
        'nama_peserta',
        'is_buyer',
    ];

    protected $casts = [
        'is_buyer' => 'boolean',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
