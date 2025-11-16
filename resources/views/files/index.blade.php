@extends('layouts.app')

@section('title', 'Files')

@section('content')
    <div class="space-y-4 text-xs">
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold text-slate-800">Files</h1>
            <a href="{{ route('files.create') }}"
               class="inline-flex items-center rounded bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
                + Upload File
            </a>
        </div>

        <div class="bg-white rounded shadow-sm p-4">
            <div class="mb-3 text-slate-500">
                Daftar file dari endpoint <code>/files/</code> GenieACS NBI.
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-xs">
                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr>
                            <th class="px-3 py-2">Filename</th>
                            <th class="px-3 py-2">fileType</th>
                            <th class="px-3 py-2">oui</th>
                            <th class="px-3 py-2">productClass</th>
                            <th class="px-3 py-2">version</th>
                            <th class="px-3 py-2 w-24">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($files as $file)
                            @php
                                $filename = $file['filename'] ?? $file['_id'] ?? '(no name)';
                            @endphp
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="px-3 py-2 align-top font-mono text-[11px]">
                                    {{ $filename }}
                                </td>
                                <td class="px-3 py-2 align-top">
                                    {{ $file['fileType'] ?? '' }}
                                </td>
                                <td class="px-3 py-2 align-top">
                                    {{ $file['oui'] ?? '' }}
                                </td>
                                <td class="px-3 py-2 align-top">
                                    {{ $file['productClass'] ?? '' }}
                                </td>
                                <td class="px-3 py-2 align-top">
                                    {{ $file['version'] ?? '' }}
                                </td>
                                <td class="px-3 py-2 align-top">
                                    <form action="{{ route('files.destroy', $filename) }}" method="POST"
                                          onsubmit="return confirm('Hapus file {{ $filename }} dari ACS?');">
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
                                <td colspan="6" class="px-3 py-4 text-center text-slate-400">
                                    Tidak ada file.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
