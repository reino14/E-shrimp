<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    use HasFactory;

    protected $table = 'sensor_data';
    protected $primaryKey = 'data_id';

    protected $fillable = [
        'robot_id',
        'waktu',
        'suhu',
        'oksigen',
        'ph',
        'salinitas',
        'kualitas_air',
    ];

    protected $casts = [
        'waktu' => 'datetime',
        'suhu' => 'double',
        'oksigen' => 'double',
        'ph' => 'double',
        'salinitas' => 'double',
    ];

    // Relationships
    public function robotKapal()
    {
        return $this->belongsTo(RobotKapalEshrimp::class, 'robot_id', 'robot_id');
    }
}


