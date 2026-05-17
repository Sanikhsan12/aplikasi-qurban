<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistribusiDaging extends Model
{
    protected $table = 'distribusi_daging';

    protected $fillable = [
        'pelaksanaan_id',
        'link_gdrive',
    ];

    public function dokumentasi()
    {
        return $this->hasMany(
            DistribusiDokumentasi::class,
            'distribusi_daging_id'
        );
    }

    public function pelaksanaan()
    {
        return $this->belongsTo(Pelaksanaan::class);
    }
}

