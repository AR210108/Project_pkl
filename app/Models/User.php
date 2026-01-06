<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'divisi'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Cek Role
    public function isOwner(): bool { 
        return $this->role === 'owner'; 
    }
    
    public function isGeneralManager(): bool { 
        return $this->role === 'general_manager'; 
    }
    
    public function isManagerDivisi(): bool { 
        return $this->role === 'manager_divisi'; 
    }
    
    public function isKaryawan(): bool { 
        return $this->role === 'karyawan'; 
    }

    // Cek Divisi
    public function isProgrammer(): bool { 
        return $this->divisi === 'programmer'; 
    }
    
    public function isDigitalMarketing(): bool { 
        return $this->divisi === 'digital_marketing'; 
    }
    
    public function isDesainer(): bool { 
        return $this->divisi === 'desainer'; 
    }

    // Helper
    public function getRoleName(): string
    {
        return match($this->role) {
            'owner' => 'Pemilik',
            'general_manager' => 'General Manager',
            'manager_divisi' => 'Manager Divisi',
            'karyawan' => 'Karyawan',
            default => '-'
        };
    }

    public function getDivisiName(): string
    {
        return match($this->divisi) {
            'programmer' => 'Programmer',
            'digital_marketing' => 'Digital Marketing',
            'desainer' => 'Desainer',
            default => '-'
        };
    }
}