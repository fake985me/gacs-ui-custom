@extends('layouts.app')

@section('title', 'Initial Setup')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Initial Setup</h1>

    <p class="mb-4 text-sm text-gray-700">
        Silakan isi konfigurasi GenieACS dan buat akun admin pertama.
    </p>

    <form method="POST" action="{{ route('setup.store') }}" class="space-y-4 max-w-xl">
        @csrf

        <div>
            <label class="block text-sm font-medium">GenieACS NBI URL</label>
            <input type="url" name="nbi_url"
                   value="{{ old('nbi_url', $default_nbi_url) }}"
                   class="mt-1 w-full border rounded px-3 py-2"
                   required
                   placeholder="http://192.168.1.11:7557">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">NBI username (optional)</label>
                <input type="text" name="nbi_username"
                       value="{{ old('nbi_username') }}"
                       class="mt-1 w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">NBI password (optional)</label>
                <input type="text" name="nbi_password"
                       value="{{ old('nbi_password') }}"
                       class="mt-1 w-full border rounded px-3 py-2">
            </div>
        </div>

        <hr class="my-4">

        <h2 class="font-semibold mb-2">Admin user</h2>

        <div>
            <label class="block text-sm font-medium">Nama</label>
            <input type="text" name="admin_name"
                   value="{{ old('admin_name') }}"
                   class="mt-1 w-full border rounded px-3 py-2"
                   required>
        </div>

        <div>
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="admin_email"
                   value="{{ old('admin_email') }}"
                   class="mt-1 w-full border rounded px-3 py-2"
                   required>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Password</label>
                <input type="password" name="admin_password"
                       class="mt-1 w-full border rounded px-3 py-2"
                       required>
            </div>
            <div>
                <label class="block text-sm font-medium">Konfirmasi Password</label>
                <input type="password" name="admin_password_confirmation"
                       class="mt-1 w-full border rounded px-3 py-2"
                       required>
            </div>
        </div>

        <button type="submit"
                class="mt-4 px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
            Simpan & Selesai
        </button>
    </form>
@endsection
