<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('karyawans', function (Blueprint $table) { // Ubah 'karyawan' menjadi 'karyawans'
            $table->unsignedBigInteger('user_id')->nullable()->after('id');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('karyawans', function (Blueprint $table) { // Ubah 'karyawan' menjadi 'karyawans'
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};