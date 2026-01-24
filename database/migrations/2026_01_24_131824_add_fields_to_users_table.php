<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Profil fields
            $table->string('username')->nullable()->after('email');
            $table->string('phone')->nullable()->after('divisi');
            $table->string('location')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('location');
            $table->string('foto')->nullable()->after('bio');
            
            // Akun preferences
            $table->string('language', 10)->default('id')->after('foto');
            $table->string('timezone', 50)->default('Asia/Jakarta')->after('language');
            $table->string('date_format', 20)->default('d/m/Y')->after('timezone');
            
            // Notifikasi preferences
            $table->boolean('email_notifications')->default(true)->after('date_format');
            $table->boolean('push_notifications')->default(true)->after('email_notifications');
            $table->boolean('sms_notifications')->default(false)->after('push_notifications');
            $table->boolean('weekly_reports')->default(false)->after('sms_notifications');
            $table->boolean('product_updates')->default(true)->after('weekly_reports');
            $table->boolean('special_offers')->default(true)->after('product_updates');
            
            // Security
            $table->boolean('two_factor_enabled')->default(false)->after('special_offers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Profil fields
            $table->dropColumn(['username', 'phone', 'location', 'bio', 'foto']);
            
            // Akun preferences
            $table->dropColumn(['language', 'timezone', 'date_format']);
            
            // Notifikasi preferences
            $table->dropColumn([
                'email_notifications', 
                'push_notifications', 
                'sms_notifications', 
                'weekly_reports', 
                'product_updates', 
                'special_offers'
            ]);
            
            // Security
            $table->dropColumn('two_factor_enabled');
        });
    }
};