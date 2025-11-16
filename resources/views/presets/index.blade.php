@extends('layouts.app')

@section('title', 'Presets')

@section('content')
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold text-slate-800">Presets</h1>
            <a href="{{ route('presets.create') }}"
               class="inline-flex items-center rounded bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
                + New Preset
            </a>
        </div>

        <div class="bg-white rounded shadow-sm p-4 text-xs">
            <div class="mb-3 text-slate-500">
                Presets diambil langsung dari endpoint <code>/presets</code> GenieACS NBI.
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-xs">
                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr>
                            <th class="px-3 py-2">Name</th>
                            <th class="px-3 py-2">Weight</th>
                            <th class="px-3 py-2">Precondition</th>
                            <th class="px-3 py-2">Configs</th>
                            <th class="px-3 py-2 w-32">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($presets as $preset)
                            @php
                                $name = $preset['_id'] ?? $preset['name'] ?? '(no id)';
                                $weight = $preset['weight'] ?? 0;
                                $precondition = $preset['precondition'] ?? '';
                                $configs = $preset['configurations'] ?? [];
                            @endphp
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="px-3 py-2 align-top font-mono text-[11px]">
                                    {{ $name }}
                                </td>
                                <td class="px-3 py-2 align-top">
                                    {{ $weight }}
                                </td>
                                <td class="px-3 py-2 align-top max-w-xs">
                                    <div class="line-clamp-2 break-all text-[11px]">
                                        {{ $precondition }}
                                    </div>
                                </td>
                                <td class="px-3 py-2 align-top">
                                    {{ is_array($configs) ? count($configs) : 0 }}
                                </td>
                                <td class="px-3 py-2 align-top space-x-1">
                                    <a href="{{ route('presets.edit', $name) }}"
                                       class="inline-flex items-center rounded border border-slate-300 px-2 py-1 text-[11px] hover:bg-slate-50">
                                        Edit
                                    </a>
                                    <form action="{{ route('presets.destroy', $name) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus preset {{ $name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center rounded border border-red-300 px-2 py-1 text-[11px] text-red-700 hover:bg-red-50">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-4 text-center text-slate-400">
                                    Tidak ada preset.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
