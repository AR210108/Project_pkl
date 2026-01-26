<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'cuti_id',
        'action',
        'changes',
        'user_id',
        'note',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function cuti()
    {
        return $this->belongsTo(Cuti::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}