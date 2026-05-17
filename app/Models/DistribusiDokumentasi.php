<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistribusiDokumentasi extends Model
{
    protected $table = 'distribusi_dokumentasi';

    protected $fillable = [
        'distribusi_daging_id',
        'file_path',
    ];

    public function distribusiDaging()
    {
        return $this->belongsTo(
            DistribusiDaging::class,
            'distribusi_daging_id'
        );
    }
}
