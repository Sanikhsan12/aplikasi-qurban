<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankPenerima extends Model
{
    protected $table = 'bank_penerima';

    protected $fillable = [
        'nama_bank',
        'no_rek',
        'as_nama',
    ];
}
