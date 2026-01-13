<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama dengan cara yang aman (disable foreign key checks)
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Task::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Ambil beberapa user untuk dijadikan contoh
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->call(UserSeeder::class);
            $users = User::all();
        }

        // Cari user dengan role tertentu
        $adminUser = $users->where('role', 'admin')->first();
        $managerDivisi = $users->where('role', 'manager_divisi')->first();
        $generalManager = $users->where('role', 'general_manager')->first();
        $financeUser = $users->where('role', 'finance')->first();
        $karyawanUsers = $users->where('role', 'karyawan');
        
        // Jika tidak ada user dengan role tertentu, gunakan user pertama
        if (!$adminUser) $adminUser = $users->first();
        if (!$managerDivisi) $managerDivisi = $users->first();
        if (!$generalManager) $generalManager = $users->first();
        if (!$financeUser) $financeUser = $users->first();
        if ($karyawanUsers->isEmpty()) $karyawanUsers = collect([$users->first()]);

        $tasks = [
            [
                'judul' => 'Website Pendidikan',
                'deskripsi' => 'Buatlah desain mockup untuk halaman landing page Website Pendidikan menggunakan Figma. Pastikan desain mengikuti brand guideline yang sudah ada. Fokus pada tampilan yang clean dan mobile-friendly.',
                'deadline' => Carbon::now()->addDays(7),
                'target_type' => 'karyawan',
                'created_by' => $adminUser->id,
                'assigned_to' => $karyawanUsers->first()->id,
                'catatan' => 'Mockup harus mencakup header, hero section, fitur, testimoni, dan footer.',
                'assigned_at' => Carbon::now()->subDays(1),
            ],
            [
                'judul' => 'Development API Payment',
                'deskripsi' => 'Develop RESTful API untuk sistem pembayaran dengan integrasi Midtrans. API harus mendukung metode pembayaran: credit card, bank transfer, e-wallet.',
                'deadline' => Carbon::now()->addDays(14),
                'target_type' => 'divisi',
                'target_divisi' => 'Programmer',
                'is_broadcast' => true,
                'created_by' => $adminUser->id,
                'catatan' => 'Pastikan implementasi webhook untuk notifikasi pembayaran.',
                'assigned_at' => Carbon::now()->subDays(2),
            ],
            [
                'judul' => 'Social Media Campaign Q1',
                'deskripsi' => 'Rencanakan dan eksekusi campaign social media untuk kuartal pertama. Fokus pada platform Instagram, LinkedIn, dan TikTok.',
                'deadline' => Carbon::now()->addDays(30),
                'target_type' => 'divisi',
                'target_divisi' => 'Digital Marketing',
                'is_broadcast' => true,
                'created_by' => $managerDivisi->id,
                'catatan' => 'Siapkan content calendar dan analisis kompetitor.',
                'assigned_at' => Carbon::now()->subDays(3),
            ],
            [
                'judul' => 'UI/UX Review Aplikasi Mobile',
                'deskripsi' => 'Lakukan review UI/UX pada aplikasi mobile versi 2.0. Berikan rekomendasi perbaikan berdasarkan best practices dan user feedback.',
                'deadline' => Carbon::now()->addDays(5),
                'target_type' => 'karyawan',
                'created_by' => $managerDivisi->id,
                'assigned_to' => $karyawanUsers->skip(1)->first()->id ?? $karyawanUsers->first()->id,
                'catatan' => 'Fokus pada user flow dan accessibility.',
                'assigned_at' => Carbon::now()->subDays(1),
            ],
            [
                'judul' => 'Maintenance Server Production',
                'deskripsi' => 'Lakukan maintenance rutin pada server production. Backup database, update security patches, dan monitor performance.',
                'deadline' => Carbon::now()->addDays(3),
                'target_type' => 'manager',
                'target_manager_id' => $managerDivisi->id,
                'created_by' => $adminUser->id,
                'catatan' => 'Lakukan di luar jam kerja untuk minimalisasi downtime.',
                'assigned_at' => Carbon::now()->subDays(5),
                'completed_at' => Carbon::now()->subDays(1),
            ],
            [
                'judul' => 'SEO Optimization Website',
                'deskripsi' => 'Optimasi SEO on-page dan technical SEO untuk website perusahaan. Target: meningkatkan ranking untuk keywords utama.',
                'deadline' => Carbon::now()->addDays(21),
                'target_type' => 'divisi',
                'target_divisi' => 'Digital Marketing',
                'is_broadcast' => true,
                'created_by' => $generalManager->id,
                'catatan' => 'Gunakan tools: Google Search Console, SEMrush, Ahrefs.',
                'assigned_at' => Carbon::now()->subDays(2),
            ],
            [
                'judul' => 'Bug Fix - Login Issue',
                'deskripsi' => 'Perbaiki bug pada fitur login yang menyebabkan timeout pada beberapa user. Investigasi root cause dan implementasikan fix.',
                'deadline' => Carbon::now()->addDays(2),
                'target_type' => 'karyawan',
                'created_by' => $adminUser->id,
                'assigned_to' => $karyawanUsers->skip(2)->first()->id ?? $karyawanUsers->first()->id,
                'catatan' => 'Bug terjadi pada user dengan koneksi internet lambat.',
                'assigned_at' => Carbon::now()->subDays(3),
                'completed_at' => Carbon::now()->subDays(1),
            ],
            [
                'judul' => 'Design System Update',
                'deskripsi' => 'Update design system dengan komponen baru dan memperbaiki konsistensi warna dan typography.',
                'deadline' => Carbon::now()->addDays(10),
                'target_type' => 'divisi',
                'target_divisi' => 'Desainer',
                'is_broadcast' => true,
                'created_by' => $managerDivisi->id,
                'catatan' => 'Sesuaikan dengan brand guidelines terbaru.',
                'assigned_at' => Carbon::now()->subDays(2),
            ],
            [
                'judul' => 'Database Optimization',
                'deskripsi' => 'Optimasi query database dan implementasi indexing untuk meningkatkan performance aplikasi.',
                'deadline' => Carbon::now()->addDays(7),
                'target_type' => 'karyawan',
                'created_by' => $adminUser->id,
                'assigned_to' => $karyawanUsers->skip(3)->first()->id ?? $karyawanUsers->first()->id,
                'catatan' => 'Fokus pada query yang sering digunakan.',
                'assigned_at' => Carbon::now()->subDays(1),
            ],
            [
                'judul' => 'Monthly Report Presentation',
                'deskripsi' => 'Siapkan presentasi untuk monthly report meeting dengan stakeholders. Sertakan data performance, achievements, dan plan ke depan.',
                'deadline' => Carbon::now()->addDays(2),
                'target_type' => 'manager',
                'target_manager_id' => $generalManager->id,
                'created_by' => $financeUser->id,
                'catatan' => 'Meeting ditunda ke minggu depan.',
                'catatan_update' => 'Dibatalkan karena jadwal berubah',
                'assigned_at' => Carbon::now()->subDays(4),
            ],
            [
                'judul' => 'User Research untuk Produk Baru',
                'deskripsi' => 'Lakukan riset pengguna untuk pengembangan produk baru. Wawancara minimal 10 pengguna potensial dan buat laporan hasil.',
                'deadline' => Carbon::now()->addDays(14),
                'target_type' => 'divisi',
                'target_divisi' => 'Desainer',
                'is_broadcast' => true,
                'created_by' => $generalManager->id,
                'catatan' => 'Fokus pada pain points dan kebutuhan pengguna.',
                'assigned_at' => Carbon::now()->subDays(1),
            ],
            [
                'judul' => 'Implementasi Fitur Notifikasi',
                'deskripsi' => 'Implementasikan sistem notifikasi real-time untuk aplikasi web menggunakan WebSocket. Notifikasi mencakup pesan, update status, dan pengingat deadline.',
                'deadline' => Carbon::now()->addDays(10),
                'target_type' => 'divisi',
                'target_divisi' => 'Programmer',
                'is_broadcast' => true,
                'created_by' => $adminUser->id,
                'catatan' => 'Pastikan kompatibilitas dengan browser utama.',
                'assigned_at' => Carbon::now()->subDays(3),
            ],
            [
                'judul' => 'Konten Blog untuk Bulan Ini',
                'deskripsi' => 'Buat 4 artikel blog untuk meningkatkan SEO dan engagement. Topik: teknologi terkini, tips produktivitas, case study, dan tutorial.',
                'deadline' => Carbon::now()->addDays(15),
                'target_type' => 'karyawan',
                'created_by' => $managerDivisi->id,
                'assigned_to' => $karyawanUsers->skip(4)->first()->id ?? $karyawanUsers->first()->id,
                'catatan' => 'Gunakan keyword research untuk optimasi SEO.',
                'assigned_at' => Carbon::now()->subDays(2),
            ],
            [
                'judul' => 'Analisis Kompetitor',
                'deskripsi' => 'Lakukan analisis mendalam terhadap 3 kompetitor utama. Fokus pada fitur, harga, strategi pemasaran, dan kekuatan/kelemahan.',
                'deadline' => Carbon::now()->addDays(7),
                'target_type' => 'manager',
                'target_manager_id' => $managerDivisi->id,
                'created_by' => $generalManager->id,
                'catatan' => 'Presentasikan findings dalam meeting strategi.',
                'assigned_at' => Carbon::now()->subDays(10),
                'completed_at' => Carbon::now()->subDays(2),
            ],
            [
                'judul' => 'Update Dokumentasi API',
                'deskripsi' => 'Perbarui dokumentasi API untuk endpoint baru yang telah dikembangkan. Sertakan contoh request/response dan error handling.',
                'deadline' => Carbon::now()->addDays(5),
                'target_type' => 'karyawan',
                'created_by' => $adminUser->id,
                'assigned_to' => $karyawanUsers->skip(5)->first()->id ?? $karyawanUsers->first()->id,
                'catatan' => 'Gunakan format standar seperti OpenAPI/Swagger.',
                'assigned_at' => Carbon::now()->subDays(1),
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }

        $this->command->info('Task seeder berhasil dijalankan!');
        $this->command->info('Total tasks created: ' . count($tasks));
        
        // Tampilkan informasi tasks
        $this->command->info("\nDetail Tasks:");
        $this->command->info("=============");
        foreach (Task::all() as $task) {
            $assignee = '-';
            if ($task->target_type === 'karyawan' && $task->assigned_to) {
                $assignee = User::find($task->assigned_to)->name ?? '-';
            } elseif ($task->target_type === 'divisi') {
                $assignee = $task->target_divisi;
            } elseif ($task->target_type === 'manager' && $task->target_manager_id) {
                $assignee = User::find($task->target_manager_id)->name ?? '-';
            }
            
            $creator = User::find($task->created_by)->name ?? '-';
            
            $this->command->info("ID: {$task->id} | Judul: {$task->judul}");
            $this->command->info("     Tipe: {$task->target_type} | Penerima: {$assignee}");
            $this->command->info("     Dibuat oleh: {$creator}");
            $this->command->info("     Deadline: {$task->deadline->format('d M Y')}");
            if ($task->completed_at) {
                $this->command->info("     Selesai pada: {$task->completed_at->format('d M Y')}");
            }
            $this->command->info("----------------------------------------");
        }
    }
}