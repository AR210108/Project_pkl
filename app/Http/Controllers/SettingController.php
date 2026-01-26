<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\Setting;
use App\Models\User;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman pengaturan kontak
     */
    public function contact()
    {
        $contactData = Setting::getContactData();
        
        return view('admin.settings.contact', compact('contactData'));
    }

    /**
     * Mengupdate data kontak
     */
    public function updateContact(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'whatsapp_message' => 'required|string|max:500'
        ]);

        try {
            // Simpan ke database
            $setting = Setting::updateOrCreate(
                ['key' => 'contact_info'],
                [
                    'value' => json_encode([
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'whatsapp_message' => $request->whatsapp_message,
                        'updated_at' => now()
                    ]),
                    'description' => 'Informasi kontak untuk landing page'
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Informasi kontak berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan data kontak untuk API
     */
    public function getContactData()
    {
        $contactData = Setting::getContactData();
        
        return response()->json([
            'success' => true,
            'data' => $contactData
        ]);
    }
}