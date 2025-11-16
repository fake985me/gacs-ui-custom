@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded shadow-sm p-4">
                <div class="text-xs font-semibold text-slate-500">Total Devices</div>
                <div class="mt-2 text-3xl font-bold text-slate-800">{{ $totalDevices }}</div>
                <div class="mt-1 text-xs text-slate-400">Diambil dari GenieACS NBI</div>
            </div>

            <div class="bg-white rounded shadow-sm p-4">
                <div class="text-xs font-semibold text-slate-500">GenieACS NBI URL</div>
                <div class="mt-2 text-sm text-slate-800">{{ config('genieacs.nbi_url') }}</div>
                <div class="mt-1 text-xs text-slate-400">Atur di .env (GENIEACS_NBI_URL)</div>
            </div>

            <div class="bg-white rounded shadow-sm p-4">
                <div class="text-xs font-semibold text-slate-500">Info</div>
                <div class="mt-2 text-sm text-slate-800">
                    Skeleton UI Laravel 10 + TR-069 actions.
                </div>
                <div class="mt-1 text-xs text-slate-400">
                    Silakan edit controller & view sesuai kebutuhan.
                </div>
            </div>
        </div>

        <div class="bg-white rounded shadow-sm p-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-700">Recent Devices</h2>
                <a href="{{ route('devices.index') }}" class="text-xs text-blue-600 hover:underline">Lihat semua</a>
            </div>

            <div class="mt-3 overflow-x-auto">
                <table class="min-w-full text-left text-xs">
                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr>
                            <th class="px-3 py-2">Device ID</th>
                            <th class="px-3 py-2">Summary</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentDevices as $device)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="px-3 py-2 align-top">
                                    <a href="{{ route('devices.show', $device['_id'] ?? '') }}" class="text-blue-600 hover:underline">
                                        {{ $device['_id'] ?? '-' }}
                                    </a>
                                </td>
                                <td class="px-3 py-2 align-top">
                                    <pre class="text-[11px] whitespace-pre-wrap break-all text-slate-600">{{ json_encode($device['summary'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-3 py-4 text-center text-slate-400">
                                    Belum ada data device (cek koneksi ke GenieACS NBI).
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
