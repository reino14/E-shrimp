<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RobotKapalEshrimp extends Model
{
    use HasFactory;

    protected $table = 'robot_kapal_eshrimps';
    protected $primaryKey = 'robot_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'robot_id',
        'email_peternak',
        'status',
        'lokasi',
    ];

    // Relationships
    public function peternak()
    {
        return $this->belongsTo(Peternak::class, 'email_peternak', 'email_peternak');
    }

    public function sensorData()
    {
        return $this->hasMany(SensorData::class, 'robot_id', 'robot_id');
    }
}


