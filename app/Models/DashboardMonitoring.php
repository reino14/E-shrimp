<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardMonitoring extends Model
{
    use HasFactory;

    protected $table = 'dashboard_monitorings';
    protected $primaryKey = 'kolam_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kolam_id',
        'email_peternak',
        'foto_posisi_kapal',
        'treshold_id',
        'notif_id',
        'data_id',
    ];

    // Relationships
    public function peternak()
    {
        return $this->belongsTo(Peternak::class, 'email_peternak', 'email_peternak');
    }

    public function thresholds()
    {
        return $this->hasMany(Threshold::class, 'kolam_id', 'kolam_id');
    }

    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'kolam_id', 'kolam_id');
    }
}


