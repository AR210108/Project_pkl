<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Comment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        // Get all tasks
        $tasks = Task::all();
        
        foreach ($tasks as $task) {
            // Add 1-3 comments per task
            $commentCount = rand(1, 3);
            
            for ($i = 0; $i < $commentCount; $i++) {
                $users = User::whereIn('role', ['karyawan', 'admin'])->get();
                if ($users->isEmpty()) continue;
                
                $commentUser = $users->random();
                
                Comment::create([
                    'content' => $this->getRandomComment(),
                    'task_id' => $task->id,
                    'user_id' => $commentUser->id,
                    'created_at' => Carbon::now()->subHours(rand(1, 72)),
                ]);
            }
        }
        
        $this->command->info('Comments added to tasks successfully!');
    }
    
    private function getRandomComment()
    {
        $comments = [
            "Tolong diperhatikan deadline nya ya. Terima kasih!",
            "Progress sudah sampai mana? Ada kendala?",
            "Bagus, keep up the good work!",
            "Jangan lupa update dokumentasi juga ya.",
            "Sudah saya review, ada beberapa catatan kecil yang perlu diperbaiki.",
            "Excellent work! Client sangat puas dengan hasilnya.",
            "Ada beberapa perubahan requirement dari client, saya akan email detailnya.",
            "Mohon prioritaskan task ini, urgent untuk client.",
            "File sudah saya upload, mohon dicek kembali.",
            "Sesuai dengan ekspektasi, terima kasih atas kerja kerasnya.",
            "Ada bug kecil yang perlu diperbaiki di bagian footer.",
            "Design sudah approved, silakan lanjut ke development.",
        ];
        
        return $comments[array_rand($comments)];
    }
}