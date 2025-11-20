<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peternak extends Model
{
    use HasFactory;

    protected $table = 'peternaks';
    protected $primaryKey = 'email_peternak';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'email_peternak',
        'nama',
        'password',
        'tracker_id',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    // Relationships
    public function robotKapals()
    {
        return $this->hasMany(RobotKapalEshrimp::class, 'email_peternak', 'email_peternak');
    }

    public function dashboards()
    {
        return $this->hasMany(DashboardMonitoring::class, 'email_peternak', 'email_peternak');
    }
}


