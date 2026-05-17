<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanaDKM extends Model
{
    protected $table = 'dana_dkm';

    protected $fillable = [
        'order_id',
        'sumber_dana',
        'jumlah_dana',
        'keterangan',
    ];

    protected $casts = [
        'jumlah_dana' => 'decimal:2',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function danaOperasional()
    {
        return $this->hasMany(DanaOperasional::class, 'id_dkm');
    }
}
