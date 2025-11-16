@extends('layouts.app')

@section('title', $name ? 'Edit Provision ' . $name : 'New Provision')

@section('content')
    @php
        $isEdit = !empty($name);
    @endphp

    <div class="space-y-4 text-xs">
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold text-slate-800">
                {{ $isEdit ? 'Edit Provision: ' . $name : 'New Provision' }}
            </h1>
            <a href="{{ route('provisions.index') }}" class="text-xs text-blue-600 hover:underline">
                &larr; Kembali ke list provisions
            </a>
        </div>

        <div class="bg-white rounded shadow-sm p-4">
            <form method="POST" action="{{ $isEdit ? route('provisions.update', $name) : route('provisions.store') }}" class="space-y-4">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <label class="block text-[11px] font-semibold text-slate-600">Name</label>
                        @if($isEdit)
                            <input type="text" class="mt-1 w-full rounded border border-slate-300 px-2 py-1 text-[11px] bg-slate-100"
                                   value="{{ $name }}" disabled>
                        @else
                            <input type="text" name="name" class="mt-1 w-full rounded border border-slate-300 px-2 py-1 text-[11px]"
                                   value="{{ old('name') }}"
                                   placeholder="contoh: myprovision" required>
                        @endif
                        <p class="mt-2 text-[10px] text-slate-400">
                            Nama ini akan menjadi <code>/provisions/&lt;name&gt;</code> di NBI.
                        </p>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">
                        Provision script (JavaScript)
                    </label>
                    <textarea name="script" rows="18"
                              class="w-full rounded border border-slate-300 px-2 py-1 font-mono text-[11px]">{{ old('script', $script) }}</textarea>
                    <p class="mt-1 text-[10px] text-slate-400">
                        Body request akan dikirim apa adanya ke
                        <code>PUT /provisions/&lt;name&gt;</code> pada GenieACS NBI.
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
