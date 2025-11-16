<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GenieAcsClient;

class DashboardController extends Controller
{
    public function index(GenieAcsClient $client)
    {
        $devices = $client->listDevices([
            'limit' => 10,
        ]);

        return view('dashboard.index', [
            'totalDevices' => $devices['total'] ?? 0,
            'recentDevices' => $devices['items'] ?? [],
        ]);
    }

    public function show(string $id, GenieAcsClient $client)
    {
        $device = $client->getDevice($id);

        if (!$device) {
            abort(404, 'Device not found');
        }

        return view('devices.show', [
            'device' => $device,
        ]);
    }

    public function reboot(string $id, GenieAcsClient $client)
    {
        $result = $client->rebootDevice($id);

        return back()->with(
            $result['status'] === 200 ? 'status' : 'error',
            'Reboot: HTTP ' . $result['status'] . ' - ' . (string) ($result['rawBody'] ?? '')
        );
    }

    public function factoryReset(string $id, GenieAcsClient $client)
    {
        $result = $client->factoryResetDevice($id);

        return back()->with(
            $result['status'] === 200 ? 'status' : 'error',
            'Factory reset: HTTP ' . $result['status'] . ' - ' . substr((string)$result['rawBody'], 0, 200)
        );
    }

    public function updateWifi(string $id, Request $request, GenieAcsClient $client)
    {
        $data = $request->validate([
            'ssid'           => 'required|string|max:64',
            'password'       => 'nullable|string|max:64',
            'ssid_param'     => 'nullable|string',
            'password_param' => 'nullable|string',
        ]);

        $options = [];
        if (!empty($data['ssid_param'])) {
            $options['ssid_param'] = $data['ssid_param'];
        }
        if (!empty($data['password_param'])) {
            $options['password_param'] = $data['password_param'];
        }

        $result = $client->setWifi(
            $id,
            $data['ssid'],
            $data['password'] ?? null,
            $options
        );

        return back()->with(
            $result['status'] === 200 ? 'status' : 'error',
            'Update WiFi: HTTP ' . $result['status'] . ' - ' . substr((string)$result['rawBody'], 0, 200)
        );
    }
}
