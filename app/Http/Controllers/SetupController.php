<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SetupController extends Controller
{
    public function show()
    {
        // Kalau sudah selesai setup, langsung ke login
        if (Setting::get('setup_completed', '0') === '1') {
            return redirect()->route('login');
        }

        return view('setup.index', [
            'default_nbi_url' => Setting::get('genieacs_nbi_url', config('genieacs.nbi_url')),
        ]);
    }

    public function store(Request $request)
    {
        if (Setting::get('setup_completed', '0') === '1') {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'nbi_url'        => 'required|url',
            'nbi_username'   => 'nullable|string|max:255',
            'nbi_password'   => 'nullable|string|max:255',
            'admin_name'     => 'required|string|max:255',
            'admin_email'    => 'required|email|max:255',
            'admin_password' => 'required|string|min:8|confirmed',
        ]);

        // Simpan konfigurasi NBI
        Setting::set('genieacs_nbi_url', rtrim($data['nbi_url'], '/'));
        Setting::set('genieacs_nbi_username', $data['nbi_username'] ?? '');
        Setting::set('genieacs_nbi_password', $data['nbi_password'] ?? '');

        // Buat admin kalau belum ada
        $user = User::where('email', $data['admin_email'])->first();
        if (!$user) {
            User::create([
                'name'     => $data['admin_name'],
                'email'    => $data['admin_email'],
                'password' => Hash::make($data['admin_password']),
                'role'     => 'admin',
            ]);
        }

        // Flag setup selesai
        Setting::set('setup_completed', '1');

        return redirect()
            ->route('login')
            ->with('status', 'Setup selesai. Silakan login dengan akun admin yang baru dibuat.');
    }
}
