@extends('layouts.app')

@section('title', $name ? 'Edit Preset ' . $name : 'New Preset')

@section('content')
    @php
        $isEdit = !empty($name);
        $configJson = json_encode($preset['configurations'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    @endphp

    <div class="space-y-4">
        <div class="mt-4">
    <label class="block text-sm font-medium mb-1">
        Tag Helper (optional)
    </label>
    <input type="text"
           name="tags_helper"
           class="border rounded px-2 py-1 w-full"
           placeholder="contoh: wifi, olt-huawei"
           value="{{ old('tags_helper') }}">
    <p class="text-xs text-gray-500 mt-1">
        Isi daftar tag (dipisah koma). Sistem akan otomatis membuat precondition JSON
        seperti: {"_tags":{"$in":["wifi","olt-huawei"]}} jika field precondition kosong.
    </p>
</div>

        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold text-slate-800">
                {{ $isEdit ? 'Edit Preset: ' . $name : 'New Preset' }}
            </h1>
            <a href="{{ route('presets.index') }}" class="text-xs text-blue-600 hover:underline">
                &larr; Kembali ke list presets
            </a>
        </div>

        <div class="bg-white rounded shadow-sm p-4 text-xs">
            <form method="POST" action="{{ $isEdit ? route('presets.update', $name) : route('presets.store') }}" class="space-y-4">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600">Name</label>
                        @if($isEdit)
                            <input type="text" class="mt-1 w-full rounded border border-slate-300 px-2 py-1 text-[11px] bg-slate-100"
                                   value="{{ $name }}" disabled>
                        @else
                            <input type="text" name="name" class="mt-1 w-full rounded border border-slate-300 px-2 py-1 text-[11px]"
                                   value="{{ old('name') }}"
                                   placeholder="contoh: inform-5min" required>
                        @endif
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600">Weight</label>
                        <input type="number" name="weight" class="mt-1 w-full rounded border border-slate-300 px-2 py-1 text-[11px]"
                               value="{{ old('weight', $preset['weight'] ?? 0) }}">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600">Precondition (JSON)</label>
                        <input type="text" name="precondition" class="mt-1 w-full rounded border border-slate-300 px-2 py-1 text-[11px]"
                               value="{{ old('precondition', $preset['precondition'] ?? '') }}"
                               placeholder='contoh: {"_tags": "test"}'>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">
                        Configurations (array JSON)
                    </label>
                    <textarea name="configurations_json" rows="10"
                              class="w-full rounded border border-slate-300 px-2 py-1 font-mono text-[11px]">{{ old('configurations_json', $configJson) }}</textarea>
                    <p class="mt-1 text-[10px] text-slate-400">
                        Format mengikuti dokumentasi GenieACS, contoh:
                        <code>[{"type":"value","name":"InternetGatewayDevice.ManagementServer.PeriodicInformEnable","value":"true"}]</code>
                    </p>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="submit"
                            class="rounded bg-blue-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
