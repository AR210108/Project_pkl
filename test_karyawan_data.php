<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$usersKaryawan = \App\Models\User::where('role', 'karyawan')->count();
$karyawanRecords = \App\Models\Karyawan::count();

echo "=== DATA CHECK ===\n";
echo "Users dengan role 'karyawan': " . $usersKaryawan . "\n";
echo "Records di tabel 'karyawan': " . $karyawanRecords . "\n";

if ($usersKaryawan > 0) {
    echo "\nSample user karyawan:\n";
    $sample = \App\Models\User::where('role', 'karyawan')->first();
    if ($sample) {
        echo "  ID: " . $sample->id . "\n";
        echo "  Name: " . $sample->name . "\n";
        echo "  Email: " . $sample->email . "\n";
        echo "  Divisi ID: " . $sample->divisi_id . "\n";
    }
}
?>
