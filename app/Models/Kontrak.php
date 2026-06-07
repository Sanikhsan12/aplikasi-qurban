<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kontrak extends Model
{
    protected $fillable = [
        'order_id',
        'nomor_kontrak',
        'file_path',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
