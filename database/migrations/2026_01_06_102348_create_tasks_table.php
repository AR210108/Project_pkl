<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('full_description')->nullable();
            $table->string('status')->default('pending'); // pending, completed, in_progress
            $table->datetime('deadline');
            $table->string('assigner')->nullable();
            $table->string('priority')->default('medium'); // low, medium, high
            $table->string('category')->nullable(); // development, design, marketing, etc.
            $table->string('file_path')->nullable();
            $table->text('file_notes')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};