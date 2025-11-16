    @extends('layouts.app')

    @section('title', 'Devices')

    @section('content')
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold text-slate-800">Devices</h1>
            <form method="GET" action="{{ route('devices.index') }}" class="flex items-center space-x-2">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari Device ID..."
                    class="border border-slate-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring focus:ring-blue-500/40">
                <button
                    type="submit"
                    class="px-3 py-1 text-xs rounded bg-blue-600 text-white hover:bg-blue-700">
                    Cari
                </button>
            </form>
        </div>

        <div class="bg-white rounded shadow-sm p-4">
            <div class="flex items-center justify-between text-xs text-slate-500 mb-3">
                <div>Total: {{ $total }} devices</div>
                <div>Page {{ $page }} / {{ $lastPage }}</div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-xs">
                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr>
                            <th class="px-3 py-2">Device ID</th>
                            <th class="px-3 py-2">Summary</th>
                            <th class="px-3 py-2 w-24">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($devices as $device)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="px-3 py-2 align-top">
                                {{ $device['_id'] ?? '-' }}
                            </td>
                            <td class="px-3 py-2 align-top">
                                <pre class="text-[11px] whitespace-pre-wrap break-all text-slate-600">
                                {{ json_encode($device['summary'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
                                </pre>
                            </td>
                            <td class="px-3 py-2 align-top">
                                @if (!empty($device['_id']))
                                <a href="{{ route('devices.show', $device['_id']) }}">
                                    {{ $device['_id'] }}
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-3 py-4 text-center text-slate-400">
                                Belum ada data device.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($lastPage > 1)
            <div class="mt-3 flex items-center justify-between text-xs">
                <div>
                    Showing {{ ($page - 1) * $limit + 1 }} -
                    {{ min($page * $limit, $total) }} of {{ $total }}
                </div>
                <div class="space-x-1">
                    @if ($page > 1)
                    <a href="{{ route('devices.index', ['page' => $page - 1, 'limit' => $limit, 'search' => $search]) }}"
                        class="px-2 py-1 border border-slate-300 rounded hover:bg-slate-50">
                        Prev
                    </a>
                    @endif
                    @if ($page < $lastPage)
                        <a href="{{ route('devices.index', ['page' => $page + 1, 'limit' => $limit, 'search' => $search]) }}"
                        class="px-2 py-1 border border-slate-300 rounded hover:bg-slate-50">
                        Next
                        </a>
                        @endif
                </div>
            </div>
            @endif
        </div>
    </div>
    @endsection