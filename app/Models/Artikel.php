<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    protected $table = 'artikels';
    protected $primaryKey = 'artikel_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'artikel_id',
        'judul',
        'konten',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}


