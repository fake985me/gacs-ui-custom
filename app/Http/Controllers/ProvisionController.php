<?php

namespace App\Http\Controllers;

use App\Services\GenieAcsClient;
use Illuminate\Http\Request;

class ProvisionController extends Controller
{
    public function index(GenieAcsClient $client)
    {
        $provisions = $client->listProvisions();

        return view('provisions.index', [
            'provisions' => $provisions,
        ]);
    }

    public function create()
    {
        return view('provisions.create');
    }

    public function store(Request $request, GenieAcsClient $client)
    {
        $data = $request->validate([
            'name'   => 'required|string',
            'script' => 'required|string',
        ]);

        $result = $client->saveProvision($data['name'], [
            'script' => $data['script'],
        ]);

        return redirect()
            ->route('provisions.index')
            ->with(
                $result['status'] === 200 ? 'status' : 'error',
                'Simpan provision: HTTP ' . $result['status'] . ' - ' . ($result['rawBody'] ?? '')
            );
    }

    public function edit(string $name, GenieAcsClient $client)
    {
        $provision = $client->getProvision($name);

        if (!$provision) {
            abort(404, 'Provision not found');
        }

        return view('provisions.edit', [
            'provision' => $provision,
        ]);
    }

    public function update(string $name, Request $request, GenieAcsClient $client)
    {
        $data = $request->validate([
            'script' => 'required|string',
        ]);

        $result = $client->saveProvision($name, [
            'script' => $data['script'],
        ]);

        return redirect()
            ->route('provisions.index')
            ->with(
                $result['status'] === 200 ? 'status' : 'error',
                'Update provision: HTTP ' . $result['status'] . ' - ' . ($result['rawBody'] ?? '')
            );
    }

    public function destroy(string $name, GenieAcsClient $client)
    {
        $result = $client->deleteProvision($name);

        return redirect()
            ->route('provisions.index')
            ->with(
                $result['status'] === 200 ? 'status' : 'error',
                'Hapus provision: HTTP ' . $result['status'] . ' - ' . ($result['rawBody'] ?? '')
            );
    }
}

