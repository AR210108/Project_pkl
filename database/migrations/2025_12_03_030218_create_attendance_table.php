<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id', 50)->default('EMP001');
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->enum('status', ['Tepat Waktu', 'Terlambat', 'Tidak Masuk', 'Cuti', 'Sakit', 'Izin', 'Dinas Luar']);
            $table->enum('status_type', ['on-time', 'late', 'no-show', 'absent']);
            $table->integer('late_minutes')->default(0);
            $table->text('reason')->nullable();
            $table->string('location', 255)->nullable();
            $table->string('purpose', 255)->nullable();
            $table->timestamps();
            
            // Unique constraint
            $table->unique(['employee_id', 'date']);
            
            // Indexes
            $table->index('date');
            $table->index('status_type');
            $table->index('employee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};