<?php

namespace Database\Seeders;

use App\Models\FinanceTransaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TaskSeeder::class,
            CommentSeeder::class,
            FinanceTransactionSeeder::class,
        ]);
    }
}