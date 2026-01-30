<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom divisi jika ada
            if (Schema::hasColumn('users', 'divisi')) {
                $table->dropColumn('divisi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Jika perlu rollback
            $table->string('divisi')->nullable();
        });
    }
};