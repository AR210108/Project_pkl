<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;

echo "=== SYNCING USERS TO KARYAWAN ===\n";

// Get all users dengan role karyawan
$users = User::where('role', 'karyawan')->get();

echo "Found " . $users->count() . " users with role 'karyawan'\n\n";

DB::beginTransaction();

try {
    foreach ($users as $user) {
        // Check if karyawan record already exists
        $exists = Karyawan::where('user_id', $user->id)->exists();
        
        if (!$exists) {
            $divisiName = null;
            if ($user->divisi_id) {
                $divisi = $user->divisi;
                $divisiName = $divisi ? $divisi->divisi : null;
            }
            
            Karyawan::create([
                'user_id' => $user->id,
                'nama' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'divisi' => $divisiName,
                'gaji' => $user->gaji,
                'alamat' => $user->alamat ?? 'Tidak ada alamat',
                'kontak' => $user->kontak ?? '-',
                'foto' => $user->foto,
                'status_kerja' => $user->status_kerja ?? 'aktif',
                'status_karyawan' => $user->status_karyawan ?? 'tetap',
            ]);
            
            echo "✓ Created karyawan record for: {$user->name}\n";
        } else {
            echo "- Already exists: {$user->name}\n";
        }
    }
    
    DB::commit();
    
    echo "\n=== SYNC COMPLETE ===\n";
    echo "Total karyawan records: " . Karyawan::count() . "\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
