<?php

namespace App\Http\Controllers;

use App\Services\GenieAcsClient;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request, GenieAcsClient $client)
    {
        $limit = (int) $request->input('limit', 25);
        $page  = max((int) $request->input('page', 1), 1);
        $skip  = ($page - 1) * $limit;

        $params = [
            'limit' => $limit,
            'skip'  => $skip,
        ];

        $search = trim((string) $request->input('search', ''));
        if ($search !== '') {
            // cari berdasarkan _id pakai regex
            $params['query'] = json_encode([
                '_id' => [
                    '$regex'   => $search,
                    '$options' => 'i',
                ],
            ]);
        }

        $result  = $client->listDevices($params);
        $devices = $result['items'] ?? [];
        $total   = $result['total'] ?? 0;
        $lastPage = $limit > 0 ? (int) ceil($total / $limit) : 1;

        return view('devices.index', [
            'devices'  => $devices,
            'total'    => $total,
            'limit'    => $limit,
            'page'     => $page,
            'lastPage' => $lastPage,
            'search'   => $search,
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

    public function reboot(Request $request, GenieAcsClient $client)
    {
        $data = $request->validate([
            'device_id' => 'required|string',
        ]);

        $result = $client->rebootDevice($data['device_id']);

        return back()->with(
            $result['status'] === 200 ? 'status' : 'error',
            'Reboot: HTTP ' . $result['status'] . ' - ' . (string) ($result['rawBody'] ?? '')
        );
    }

    public function factoryReset(Request $request, GenieAcsClient $client)
    {
        $data = $request->validate([
            'device_id' => 'required|string',
        ]);

        $result = $client->factoryResetDevice($data['device_id']);

        return back()->with(
            $result['status'] === 200 ? 'status' : 'error',
            'Factory reset: HTTP ' . $result['status'] . ' - ' . (string) ($result['rawBody'] ?? '')
        );
    }

    public function updateWifi(Request $request, GenieAcsClient $client)
    {
        $data = $request->validate([
            'device_id'       => 'required|string',
            'ssid'            => 'required|string|max:64',
            'password'        => 'nullable|string|max:64',
            'ssid_param'      => 'nullable|string',
            'password_param'  => 'nullable|string',
        ]);

        $options = [];
        if (!empty($data['ssid_param'])) {
            $options['ssid_param'] = $data['ssid_param'];
        }
        if (!empty($data['password_param'])) {
            $options['password_param'] = $data['password_param'];
        }

        $result = $client->setWifi(
            $data['device_id'],
            $data['ssid'],
            $data['password'] ?? null,
            $options
        );

        return back()->with(
            $result['status'] === 200 ? 'status' : 'error',
            'Update WiFi: HTTP ' . $result['status'] . ' - ' . (string) ($result['rawBody'] ?? '')
        );
    }

    public function downloadFirmware(Request $request, GenieAcsClient $client)
    {
        $data = $request->validate([
            'device_id'      => 'required|string',
            'file'           => 'required|string',
            'fileType'       => 'nullable|string',
            'targetFileName' => 'nullable|string',
            'username'       => 'nullable|string',
            'password'       => 'nullable|string',
        ]);

        $result = $client->downloadFirmware($data['device_id'], $data['file'], $data);

        return back()->with(
            $result['status'] === 200 ? 'status' : 'error',
            'Download firmware: HTTP ' . $result['status'] . ' - ' . (string) ($result['rawBody'] ?? '')
        );
    }
}
