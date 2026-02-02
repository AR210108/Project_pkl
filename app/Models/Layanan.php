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
        return $this->hasMany(Project::class, 'layanan_id');
    }
    
    /**
     * Event ketika layanan diupdate
     */
    protected static function boot()
    {
        parent::boot();
        
        // Ketika layanan diupdate, update juga semua project terkait
        static::updated(function ($layanan) {
            $changedFields = [];
            
            // Cek field mana yang berubah
            if ($layanan->isDirty('nama_layanan')) {
                $changedFields['nama'] = $layanan->nama_layanan;
            }
            
            if ($layanan->isDirty('deskripsi')) {
                $changedFields['deskripsi'] = $layanan->deskripsi;
            }
            
            if ($layanan->isDirty('harga')) {
                $changedFields['harga'] = $layanan->harga;
            }
            
            // Update semua project yang terkait
            if (!empty($changedFields)) {
                $layanan->projects()->update($changedFields);
            }
        });
    }
}