<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory;

    protected $table = 'forums';
    protected $primaryKey = 'forum_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'forum_id',
        'judul',
        'isi',
        'tanggal',
        'post_peternak_id',
        'email_peternak',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relationships
    public function peternak()
    {
        return $this->belongsTo(Peternak::class, 'email_peternak', 'email_peternak');
    }
}


