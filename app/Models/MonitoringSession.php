<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringSession extends Model
{
    use HasFactory;

    protected $table = 'monitoring_sessions';
    protected $primaryKey = 'session_id';

    protected $fillable = [
        'kolam_id',
        'nama_kapal',
        'umur_budidaya',
        'threshold_oksigen',
        'threshold_ph',
        'threshold_salinitas',
        'threshold_suhu',
        'timer_monitoring',
        'mulai_monitoring',
        'selesai_monitoring',
        'is_active',
        'paused_at',
        'resumed_at',
        'total_paused_seconds',
        'is_paused',
    ];

    protected $casts = [
        'umur_budidaya' => 'integer',
        // Threshold fields are stored as JSON strings (text), so don't cast them
        // 'threshold_oksigen' => 'double', // Removed - stored as JSON string
        // 'threshold_ph' => 'double', // Removed - stored as JSON string
        // 'threshold_salinitas' => 'double', // Removed - stored as JSON string
        // 'threshold_suhu' => 'double', // Removed - stored as JSON string
        'timer_monitoring' => 'string',
        'mulai_monitoring' => 'datetime',
        'selesai_monitoring' => 'datetime',
        'is_active' => 'boolean',
        'paused_at' => 'datetime',
        'resumed_at' => 'datetime',
        'total_paused_seconds' => 'integer',
        'is_paused' => 'boolean',
    ];

    // Relationships
    public function kolam()
    {
        return $this->belongsTo(DashboardMonitoring::class, 'kolam_id', 'kolam_id');
    }

    public function sensorData()
    {
        return SensorData::where('kolam_id', $this->kolam_id)
            ->where('umur_budidaya', $this->umur_budidaya);
    }
}

