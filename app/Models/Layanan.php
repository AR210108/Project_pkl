<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'harga',
        'foto',
    ];
    public function projects()
{
    return $this->hasMany(Project::class);
}
}