<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Threshold extends Model
{
    use HasFactory;

    protected $table = 'thresholds';
    protected $primaryKey = 'treshold_id';

    protected $fillable = [
        'kolam_id',
        'sensor_tipe',
        'nilai',
        'timer',
    ];

    protected $casts = [
        'nilai' => 'double',
        'timer' => 'datetime',
    ];

    // Relationships
    public function dashboard()
    {
        return $this->belongsTo(DashboardMonitoring::class, 'kolam_id', 'kolam_id');
    }
}


