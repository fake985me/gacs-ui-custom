@extends('layouts.app')

@section('title', 'Upload File')

@section('content')
    <div class="space-y-4 text-xs">
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold text-slate-800">Upload File ke GenieACS</h1>
            <a href="{{ route('files.index') }}" class="text-xs text-blue-600 hover:underline">
                &larr; Kembali ke list files
            </a>
        </div>

        <div class="bg-white rounded shadow-sm p-4">
            <form method="POST" action="{{ route('files.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600">File</label>
                        <input type="file" name="file" required
                               class="mt-1 block w-full text-[11px] text-slate-700">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600">Filename (opsional)</label>
                        <input type="text" name="file_name"
                               class="mt-1 w-full rounded border border-slate-300 px-2 py-1 text-[11px]"
                               placeholder="Kalau kosong pakai nama file asli">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600">fileType</label>
                        <input type="text" name="fileType"
                               class="mt-1 w-full rounded border border-slate-300 px-2 py-1 text-[11px]"
                               placeholder='contoh: 1 Firmware Upgrade Image'>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600">oui</label>
                        <input type="text" name="oui"
                               class="mt-1 w-full rounded border border-slate-300 px-2 py-1 text-[11px]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600">productClass</label>
                        <input type="text" name="productClass"
                               class="mt-1 w-full rounded border border-slate-300 px-2 py-1 text-[11px]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600">version</label>
                        <input type="text" name="version"
                               class="mt-1 w-full rounded border border-slate-300 px-2 py-1 text-[11px]">
                    </div>
                </div>

                <p class="text-[10px] text-slate-400">
                    Field di atas akan dikirim sebagai header
                    <code>fileType</code>, <code>oui</code>, <code>productClass</code>, dan <code>version</code>
                    ke endpoint <code>PUT /files/&lt;filename&gt;</code> GenieACS NBI.
                </p>

                <div class="flex justify-end space-x-2">
                    <button type="submit"
                            class="rounded bg-blue-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
