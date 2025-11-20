<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasis';
    protected $primaryKey = 'notif_id';

    protected $fillable = [
        'kolam_id',
        'pesan',
        'waktu',
        'status',
    ];

    protected $casts = [
        'waktu' => 'datetime',
        'status' => 'boolean',
    ];

    // Relationships
    public function dashboard()
    {
        return $this->belongsTo(DashboardMonitoring::class, 'kolam_id', 'kolam_id');
    }
}


