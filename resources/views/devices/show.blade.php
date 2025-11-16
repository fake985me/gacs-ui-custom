@extends('layouts.app')

@section('title', 'Device ' . ($device['_id'] ?? 'Detail'))

@section('content')
    <h1 class="text-2xl font-bold mb-4">
        Device: {{ $device['_id'] ?? 'Unknown' }}
    </h1>

    {{-- Info singkat device --}}
    <div class="mb-6 p-4 bg-white rounded shadow">
        <p><strong>_id:</strong> {{ $device['_id'] ?? '-' }}</p>
        <p><strong>SerialNumber:</strong> {{ $device['SerialNumber'] ?? '-' }}</p>
        <p><strong>OUI / ProductClass:</strong>
            {{ data_get($device, 'DeviceID.OUI', '-') }}
            /
            {{ data_get($device, 'DeviceID.ProductClass', '-') }}
        </p>
    </div>

    {{-- ACTIONS TR-069 – hanya admin --}}
    @if(auth()->check() && auth()->user()->role === 'admin')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Panel Reboot / Factory Reset --}}
            <div class="p-4 bg-white rounded shadow">
                <h2 class="font-semibold mb-3">Actions</h2>

                {{-- Reboot --}}
                <form method="POST" action="{{ route('devices.reboot') }}" class="inline-block mr-2 mb-2">
                    @csrf
                    <input type="hidden" name="device_id" value="{{ $device['_id'] }}">
                    <button type="submit"
                            class="px-3 py-1 text-sm rounded bg-red-600 text-white hover:bg-red-700"
                            onclick="return confirm('Reboot perangkat ini?')">
                        Reboot
                    </button>
                </form>

                {{-- Factory Reset --}}
                <form method="POST" action="{{ route('devices.factoryReset') }}" class="inline-block mb-2">
                    @csrf
                    <input type="hidden" name="device_id" value="{{ $device['_id'] }}">
                    <button type="submit"
                            class="px-3 py-1 text-sm rounded bg-orange-600 text-white hover:bg-orange-700"
                            onclick="return confirm('Factory reset perangkat ini? Semua konfigurasi bisa hilang!')">
                        Factory Reset
                    </button>
                </form>
            </div>

            {{-- Panel WiFi --}}
            <div class="p-4 bg-white rounded shadow">
                <h2 class="font-semibold mb-3">Update WiFi</h2>

                <form method="POST" action="{{ route('devices.updateWifi') }}" class="space-y-2">
                    @csrf
                    <input type="hidden" name="device_id" value="{{ $device['_id'] }}">

                    <div>
                        <label class="block text-sm font-medium">SSID</label>
                        <input name="ssid" type="text" class="mt-1 w-full border rounded px-2 py-1"
                               required placeholder="MyWifi-5G">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Password (opsional)</label>
                        <input name="password" type="text" class="mt-1 w-full border rounded px-2 py-1"
                               placeholder="biarkan kosong kalau tidak diubah">
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-medium">SSID param path</label>
                            <input name="ssid_param" type="text" class="mt-1 w-full border rounded px-2 py-1 text-xs"
                                   placeholder="InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.SSID">
                        </div>
                        <div>
                            <label class="block text-xs font-medium">Password param path</label>
                            <input name="password_param" type="text" class="mt-1 w-full border rounded px-2 py-1 text-xs"
                                   placeholder="InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.PreSharedKey.1.PreSharedKey">
                        </div>
                    </div>

                    <button type="submit"
                            class="mt-2 px-3 py-1 text-sm rounded bg-blue-600 text-white hover:bg-blue-700">
                        Apply WiFi
                    </button>
                </form>
            </div>

            {{-- Panel Download Firmware --}}
            <div class="p-4 bg-white rounded shadow md:col-span-2">
                <h2 class="font-semibold mb-3">Download Firmware</h2>

                <form method="POST" action="{{ route('devices.download') }}" class="space-y-2">
                    @csrf
                    <input type="hidden" name="device_id" value="{{ $device['_id'] }}">

                    <div>
                        <label class="block text-sm font-medium">File (ID / nama di /files)</label>
                        <input name="file" type="text" class="mt-1 w-full border rounded px-2 py-1"
                               placeholder="contoh: mipsbe-6-42-lite.xml" required>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-medium">fileType</label>
                            <input name="fileType" type="text" class="mt-1 w-full border rounded px-2 py-1 text-xs"
                                   placeholder="1 Firmware Upgrade Image">
                        </div>
                        <div>
                            <label class="block text-xs font-medium">Target file name</label>
                            <input name="targetFileName" type="text" class="mt-1 w-full border rounded px-2 py-1 text-xs"
                                   placeholder="optional">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-medium">Username (optional)</label>
                            <input name="username" type="text" class="mt-1 w-full border rounded px-2 py-1 text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-medium">Password (optional)</label>
                            <input name="password" type="text" class="mt-1 w-full border rounded px-2 py-1 text-xs">
                        </div>
                    </div>

                    <button type="submit"
                            class="mt-2 px-3 py-1 text-sm rounded bg-indigo-600 text-white hover:bg-indigo-700"
                            onclick="return confirm('Kirim task download firmware ke perangkat ini?')">
                        Download Firmware
                    </button>
                </form>
            </div>
        </div>
    @else
        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded">
            <p class="text-sm text-yellow-800">
                Anda login sebagai readonly. Aksi TR-069 (Reboot, Factory Reset, ganti SSID, download firmware)
                hanya tersedia untuk user dengan role <code>admin</code>.
            </p>
        </div>
    @endif
@endsection
