<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use App\Models\Comment;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run()
    {
        // Ambil user yang akan menjadi penerima tugas
        $users = User::whereIn('role', ['karyawan', 'admin'])->get();
        $admin = User::where('role', 'admin')->first();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        // Data dummy tugas
        $tasks = [
            [
                'title' => 'Website Pendidikan',
                'description' => 'Dari project management',
                'full_description' => 'Buatlah desain mockup untuk halaman landing page Website Pendidikan menggunakan Figma. Pastikan desain mengikuti brand guideline yang sudah ada (lihat file di lampiran). Fokus pada tampilan yang clean dan mobile-friendly. Mockup harus mencakup header, hero section, fitur, testimoni, dan footer.',
                'status' => 'pending',
                'deadline' => Carbon::now()->addDays(7),
                'assigner' => 'Project Manager',
                'priority' => 'high',
                'category' => 'design',
                'assigned_by' => $admin ? $admin->id : null,
            ],
            [
                'title' => 'Website Makanan',
                'description' => 'Dari project management',
                'full_description' => 'Buatlah desain mockup untuk halaman landing page Website Makanan menggunakan Figma. Pastikan desain mengikuti brand guideline yang sudah ada (lihat file di lampiran). Fokus pada tampilan yang clean dan mobile-friendly. Mockup harus mencakup header, hero section, menu, galeri, dan footer.',
                'status' => 'pending',
                'deadline' => Carbon::now()->addDays(10),
                'assigner' => 'Project Manager',
                'priority' => 'medium',
                'category' => 'design',
                'assigned_by' => $admin ? $admin->id : null,
            ],
            [
                'title' => 'Website Industri',
                'description' => 'Dari project management',
                'full_description' => 'Buatlah desain mockup untuk halaman landing page Website Industri menggunakan Figma. Pastikan desain mengikuti brand guideline yang sudah ada (lihat file di lampiran). Fokus pada tampilan yang clean dan mobile-friendly. Mockup harus mencakup header, hero section, layanan, portofolio, dan footer.',
                'status' => 'completed',
                'deadline' => Carbon::now()->subDays(2),
                'assigner' => 'Project Manager',
                'priority' => 'low',
                'category' => 'design',
                'completed_at' => Carbon::now()->subDays(1),
                'assigned_by' => $admin ? $admin->id : null,
            ],
            [
                'title' => 'Aplikasi Mobile Banking',
                'description' => 'Pengembangan fitur transfer',
                'full_description' => 'Kembangkan fitur transfer antar bank pada aplikasi mobile banking. Fitur harus mencakup: validasi rekening, konfirmasi OTP, riwayat transaksi, dan notifikasi real-time. Pastikan UI/UX mengikuti design system yang sudah ada.',
                'status' => 'in_progress',
                'deadline' => Carbon::now()->addDays(14),
                'assigner' => 'Tech Lead',
                'priority' => 'high',
                'category' => 'development',
                'assigned_by' => $admin ? $admin->id : null,
            ],
            [
                'title' => 'Dashboard Analytics',
                'description' => 'Update dashboard admin',
                'full_description' => 'Perbarui dashboard analytics dengan menambahkan grafik interaktif untuk penjualan bulanan, user engagement, dan conversion rate. Gunakan Chart.js untuk visualisasi data dan pastikan responsif di semua device.',
                'status' => 'pending',
                'deadline' => Carbon::now()->addDays(5),
                'assigner' => 'Product Manager',
                'priority' => 'medium',
                'category' => 'development',
                'assigned_by' => $admin ? $admin->id : null,
            ],
            [
                'title' => 'Konten Media Sosial',
                'description' => 'Campaign bulan Desember',
                'full_description' => 'Buat konten media sosial untuk campaign bulan Desember. Minimal 12 post (Instagram, Facebook, Twitter) dengan tema promosi Natal dan Tahun Baru. Sertakan copywriting yang menarik dan visual yang sesuai brand identity.',
                'status' => 'pending',
                'deadline' => Carbon::now()->addDays(3),
                'assigner' => 'Marketing Manager',
                'priority' => 'high',
                'category' => 'marketing',
                'assigned_by' => $admin ? $admin->id : null,
            ],
            [
                'title' => 'Optimasi SEO Website',
                'description' => 'Audit dan optimasi SEO',
                'full_description' => 'Lakukan audit SEO lengkap untuk website company profile. Fokus pada: meta tags optimization, image alt text, internal linking, page speed optimization, dan mobile responsiveness. Buat laporan hasil audit dan rekomendasi perbaikan.',
                'status' => 'completed',
                'deadline' => Carbon::now()->subDays(5),
                'assigner' => 'Digital Marketing Lead',
                'priority' => 'medium',
                'category' => 'marketing',
                'completed_at' => Carbon::now()->subDays(3),
                'assigned_by' => $admin ? $admin->id : null,
            ],
            [
                'title' => 'API Integration Payment Gateway',
                'description' => 'Integrasi Midtrans',
                'full_description' => 'Integrasikan payment gateway Midtrans ke sistem e-commerce. Implementasi harus mencakup: pembayaran dengan kartu kredit, e-wallet (GoPay, OVO, Dana), dan virtual account. Pastikan security compliance PCI DSS.',
                'status' => 'in_progress',
                'deadline' => Carbon::now()->addDays(21),
                'assigner' => 'Backend Lead',
                'priority' => 'high',
                'category' => 'development',
                'assigned_by' => $admin ? $admin->id : null,
            ],
            [
                'title' => 'Video Promosi Produk',
                'description' => 'Produk baru launching',
                'full_description' => 'Buat video promosi untuk launching produk baru. Durasi 2-3 menit dengan konsep storytelling yang menarik. Sertakan subtitle, background music, dan call-to-action yang jelas. Video harus dalam format 16:9 dan 1:1 untuk social media.',
                'status' => 'pending',
                'deadline' => Carbon::now()->addDays(12),
                'assigner' => 'Creative Director',
                'priority' => 'medium',
                'category' => 'design',
                'assigned_by' => $admin ? $admin->id : null,
            ],
            [
                'title' => 'Database Migration',
                'description' => 'Migrasi data lama ke sistem baru',
                'full_description' => 'Lakukan migrasi data customer dari sistem lama ke sistem baru. Pastikan data integrity, backup sebelum migrasi, dan validasi setelah migrasi. Buat dokumentasi proses migrasi dan rollback plan.',
                'status' => 'pending',
                'deadline' => Carbon::now()->addDays(30),
                'assigner' => 'Database Administrator',
                'priority' => 'low',
                'category' => 'development',
                'assigned_by' => $admin ? $admin->id : null,
            ],
        ];

        // Assign tasks to users randomly
        foreach ($tasks as $taskData) {
            $user = $users->random();
            $task = Task::create(array_merge($taskData, ['user_id' => $user->id]));
            
            // Add comments for some tasks
            $this->addCommentsToTask($task, $users, $admin);
        }

        $this->command->info('Created ' . count($tasks) . ' dummy tasks successfully!');
    }

    private function addCommentsToTask(Task $task, $users, $admin)
    {
        // Only add comments to some tasks (randomly)
        if (rand(1, 10) <= 6) { // 60% chance to have comments
            $commentCount = rand(1, 3);
            
            for ($i = 0; $i < $commentCount; $i++) {
                $commentUser = $users->random();
                $comments = [
                    "Tolong diperhatikan deadline nya ya. Terima kasih!",
                    "Progress sudah sampai mana? Ada kendala?",
                    "Bagus, keep up the good work!",
                    "Jangan lupa update dokumentasi juga ya.",
                    "Sudah saya review, ada beberapa catatan kecil yang perlu diperbaiki.",
                    "Excellent work! Client sangat puas dengan hasilnya.",
                    "Ada beberapa perubahan requirement dari client, saya akan email detailnya.",
                    "Mohon prioritaskan task ini, urgent untuk client.",
                ];
                
                Comment::create([
                    'content' => $comments[array_rand($comments)],
                    'task_id' => $task->id,
                    'user_id' => $commentUser->id,
                    'created_at' => Carbon::now()->subHours(rand(1, 72)),
                ]);
            }
        }
    }
}