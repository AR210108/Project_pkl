<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('catatan_rapats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('peserta');
            $table->string('topik');
            $table->text('hasil_diskusi');
            $table->text('keputusan');
            $table->string('penugasan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('catatan_rapats');
    }
    
};