<?php

namespace App\Http\Controllers;

use App\Services\GenieAcsClient;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index(GenieAcsClient $client)
    {
        $files = $client->listFiles();

        return view('files.index', [
            'files' => $files,
        ]);
    }

    public function create()
    {
        return view('files.create');
    }

    public function store(Request $request, GenieAcsClient $client)
    {
        $data = $request->validate([
            'name'     => 'required|string',
            'fileType' => 'nullable|string',
            'metadata' => 'nullable|string',
        ]);

        $payload = [
            'fileType' => $data['fileType'] ?? null,
        ];

        if (!empty($data['metadata'])) {
            $payload['metadata'] = $data['metadata'];
        }

        $result = $client->saveFile($data['name'], $payload);

        return redirect()
            ->route('files.index')
            ->with(
                $result['status'] === 200 ? 'status' : 'error',
                'Simpan file: HTTP ' . $result['status'] . ' - ' . ($result['rawBody'] ?? '')
            );
    }

    public function destroy(string $name, GenieAcsClient $client)
    {
        $result = $client->deleteFile($name);

        return redirect()
            ->route('files.index')
            ->with(
                $result['status'] === 200 ? 'status' : 'error',
                'Hapus file: HTTP ' . $result['status'] . ' - ' . ($result['rawBody'] ?? '')
            );
    }
}

