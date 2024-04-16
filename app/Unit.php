<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unit extends Model
{
    protected $table = 'units';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    
    protected $fillable = [
        'name',
        'kode_unit',
        'id_parent',
        'type',
        'status',
        'periode_pembayaran',
        'nama_penghuni',
        'no_identitas',
        'alamat',
        'provinsi',
        'kota',
        'kode_pos',
        'tanggal_mulai',
    ];

    public function perumahan(): BelongsTo {
        return $this->belongsTo(Perumahan::class, 'id_parent', 'id');
    }
}
