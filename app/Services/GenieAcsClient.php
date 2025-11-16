<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use App\Models\Setting;

class GenieAcsClient
{
    /**
     * HTTP client dasar ke NBI GenieACS.
     */
    protected function client(): PendingRequest
    {
        $nbiUrl   = rtrim(Setting::get('genieacs_nbi_url', config('genieacs.nbi_url')), '/');
        $username = Setting::get('genieacs_nbi_username', config('genieacs.username'));
        $password = Setting::get('genieacs_nbi_password', config('genieacs.password'));

        $client = Http::baseUrl($nbiUrl)
            ->acceptJson()
            ->timeout(config('genieacs.timeout', 10));

        if (!empty($username)) {
            $client = $client->withBasicAuth($username, $password);
        }

        return $client;
    }

        /**
     * Ambil semua presets dari GenieACS NBI.
     *
     * Contoh pemakaian:
     *   $presets = $client->listPresets();
     *   foreach ($presets as $preset) { ... }
     *
     * Kalau mau filter pakai query Mongo:
     *   $client->listPresets(['query' => json_encode([...])]);
     */
    public function listPresets(array $params = []): array
    {
        $client = $this->client();

        if (!empty($params)) {
            $client = $client->withQueryParameters($params);
        }

        try {
            // Sesuai docs: GET /presets/?query=...
            $response = $client->get('/presets/');
        } catch (ConnectionException $e) {
            return [];
        }

        if ($response->failed()) {
            return [];
        }

        $data = $response->json();

        return is_array($data) ? $data : [];
    }

        /**
     * Ambil satu preset berdasarkan nama.
     *
     * Di NBI, presets disimpan sebagai dokumen di koleksi "presets"
     * dengan field "_id" = nama preset.
     */
    public function getPreset(string $name): ?array
    {
        // Pakai GET /presets/?query={"_id":"nama"}
        $queryJson = json_encode(['_id' => $name]);

        try {
            $response = $this->client()
                ->withQueryParameters(['query' => $queryJson])
                ->get('/presets/');
        } catch (ConnectionException $e) {
            return null;
        }

        if ($response->failed()) {
            return null;
        }

        $items = $response->json() ?? [];

        return is_array($items) && count($items) ? $items[0] : null;
    }

    /**
     * Simpan (create/update) preset.
     *
     * $data biasanya berisi:
     *  - 'weight'
     *  - 'precondition'
     *  - 'config'
     */
    public function savePreset(string $name, array $data): array
    {
        try {
            // PUT /presets/<name>
            $response = $this->client()
                ->put('/presets/' . rawurlencode($name), $data);

            return [
                'status'  => $response->status(),
                'body'    => $response->json(),
                'rawBody' => $response->body(),
            ];
        } catch (ConnectionException $e) {
            return [
                'status'  => 0,
                'body'    => null,
                'rawBody' => $e->getMessage(),
            ];
        }
    }

        /**
     * Ambil semua provisions dari GenieACS NBI.
     *
     * Contoh pemakaian:
     *   $provisions = $client->listProvisions();
     *   $provisions = $client->listProvisions(['query' => json_encode([...])]);
     */
    public function listProvisions(array $params = []): array
    {
        $client = $this->client();

        if (!empty($params)) {
            $client = $client->withQueryParameters($params);
        }

        try {
            // Sesuai API NBI: GET /provisions/
            $response = $client->get('/provisions/');
        } catch (ConnectionException $e) {
            return [];
        }

        if ($response->failed()) {
            return [];
        }

        $data = $response->json();

        return is_array($data) ? $data : [];
    }

        /**
     * LIST FILES
     * Ambil semua file dari GenieACS NBI.
     *
     * Contoh:
     *  $files = $client->listFiles();
     *  $files = $client->listFiles(['query' => json_encode([...])]);
     */
    public function listFiles(array $params = []): array
    {
        $client = $this->client();

        if (!empty($params)) {
            $client = $client->withQueryParameters($params);
        }

        try {
            // API: GET /files/
            $response = $client->get('/files/');
        } catch (ConnectionException $e) {
            return [];
        }

        if ($response->failed()) {
            return [];
        }

        $data = $response->json();

        return is_array($data) ? $data : [];
    }

    /**
     * GET FILE
     * Ambil satu file berdasarkan nama (_id).
     */
    public function getFile(string $name): ?array
    {
        $queryJson = json_encode(['_id' => $name]);

        try {
            $response = $this->client()
                ->withQueryParameters(['query' => $queryJson])
                ->get('/files/');
        } catch (ConnectionException $e) {
            return null;
        }

        if ($response->failed()) {
            return null;
        }

        $items = $response->json() ?? [];

        return is_array($items) && count($items) ? $items[0] : null;
    }

    /**
     * UPLOAD / SAVE FILE
     * Simpan (create/update) metadata file.
     *
     * Catatan:
     * - Kalau kamu cuma pakai metadata (mis. "name", "metadata"),
     *   ini cukup.
     * - Kalau mau upload binary file sungguhan, nanti bisa kita
     *   sesuaikan lagi dengan API upload GenieACS.
     */
    public function uploadFile(string $name, array $data): array
    {
        try {
            // PUT /files/<name>
            $response = $this->client()
                ->put('/files/' . rawurlencode($name), $data);

            return [
                'status'  => $response->status(),
                'body'    => $response->json(),
                'rawBody' => $response->body(),
            ];
        } catch (ConnectionException $e) {
            return [
                'status'  => 0,
                'body'    => null,
                'rawBody' => $e->getMessage(),
            ];
        }
    }

    /**
     * Alias supaya kalau di controller kamu pakai saveFile(),
     * nggak error.
     */
    public function saveFile(string $name, array $data): array
    {
        return $this->uploadFile($name, $data);
    }

    /**
     * DELETE FILE
     * Hapus satu file dari NBI.
     */
    public function deleteFile(string $name): array
    {
        try {
            $response = $this->client()
                ->delete('/files/' . rawurlencode($name));

            return [
                'status'  => $response->status(),
                'body'    => $response->json(),
                'rawBody' => $response->body(),
            ];
        } catch (ConnectionException $e) {
            return [
                'status'  => 0,
                'body'    => null,
                'rawBody' => $e->getMessage(),
            ];
        }
    }

    /**
     * Ambil satu provision berdasarkan nama.
     *
     * Di NBI, provisions disimpan sebagai dokumen dengan _id = nama provision.
     */
    public function getProvision(string $name): ?array
    {
        $queryJson = json_encode(['_id' => $name]);

        try {
            $response = $this->client()
                ->withQueryParameters(['query' => $queryJson])
                ->get('/provisions/');
        } catch (ConnectionException $e) {
            return null;
        }

        if ($response->failed()) {
            return null;
        }

        $items = $response->json() ?? [];

        return is_array($items) && count($items) ? $items[0] : null;
    }

    /**
     * Simpan (create/update) satu provision.
     *
     * $data biasanya berisi field-field seperti:
     *  - script (isi JS provision)
     *  - atau struktur lain sesuai yang kamu kirim dari form.
     */
    public function saveProvision(string $name, array $data): array
    {
        try {
            // PUT /provisions/<name>
            $response = $this->client()
                ->put('/provisions/' . rawurlencode($name), $data);

            return [
                'status'  => $response->status(),
                'body'    => $response->json(),
                'rawBody' => $response->body(),
            ];
        } catch (ConnectionException $e) {
            return [
                'status'  => 0,
                'body'    => null,
                'rawBody' => $e->getMessage(),
            ];
        }
    }

    /**
     * Hapus satu provision.
     */
    public function deleteProvision(string $name): array
    {
        try {
            $response = $this->client()
                ->delete('/provisions/' . rawurlencode($name));

            return [
                'status'  => $response->status(),
                'body'    => $response->json(),
                'rawBody' => $response->body(),
            ];
        } catch (ConnectionException $e) {
            return [
                'status'  => 0,
                'body'    => null,
                'rawBody' => $e->getMessage(),
            ];
        }
    }

    /**
     * Hapus preset.
     */
    public function deletePreset(string $name): array
    {
        try {
            $response = $this->client()
                ->delete('/presets/' . rawurlencode($name));

            return [
                'status'  => $response->status(),
                'body'    => $response->json(),
                'rawBody' => $response->body(),
            ];
        } catch (ConnectionException $e) {
            return [
                'status'  => 0,
                'body'    => null,
                'rawBody' => $e->getMessage(),
            ];
        }
    }

    /**
     * List devices dari NBI /devices
     *
     * $params boleh berisi:
     *  - 'query' => string JSON
     *  - 'limit' => int
     *  - 'skip'  => int
     *  - 'sort'  => string JSON (opsional)
     *
     * Return:
     *  [
     *    'items' => [...],
     *    'total' => int,
     *  ]
     */
    public function listDevices(array $params = []): array
    {
        $client = $this->client();

        if (!empty($params)) {
            $client = $client->withQueryParameters($params);
        }

        try {
            $response = $client->get('/devices/');
        } catch (ConnectionException $e) {
            return [
                'items' => [],
                'total' => 0,
            ];
        }

        if ($response->failed()) {
            return [
                'items' => [],
                'total' => 0,
            ];
        }

        $items = $response->json() ?? [];
        $total = (int) ($response->header('total') ?? (is_array($items) ? count($items) : 0));

        return [
            'items' => is_array($items) ? $items : [],
            'total' => $total,
        ];
    }

    /**
     * Ambil satu device berdasarkan _id dari NBI.
     *
     * $deviceId = persis _id dari ACS, contoh: "E48D8C-hAP%20lite-HG709VJ17JJ".
     */
    public function getDevice(string $deviceId): ?array
    {
        // deviceId dari route bisa dalam bentuk:
        // - "E48D8C-hAP lite-HG709VJ17JJ"   (hasil decode dari %20)
        // - "E48D8C-hAP%20lite-HG709VJ17JJ" (kalau dipanggil manual)
        //
        // Di ACS, _id yang kita lihat adalah "E48D8C-hAP%20lite-HG709VJ17JJ"
        // Jadi kita coba beberapa kandidat:

        $raw       = $deviceId;
        $decoded   = urldecode($deviceId);             // kalau ada %20 → spasi
        $encoded20 = str_replace(' ', '%20', $decoded); // spasi → %20

        $candidates = array_values(array_unique(array_filter([
            $raw,
            $decoded,
            $encoded20,
        ], fn($v) => $v !== '')));

        foreach ($candidates as $candidate) {
            $queryJson = json_encode(['_id' => $candidate]);

            try {
                $response = $this->client()
                    ->withQueryParameters(['query' => $queryJson])
                    ->get('/devices/');
            } catch (ConnectionException $e) {
                // coba kandidat berikutnya
                continue;
            }

            if ($response->failed()) {
                continue;
            }

            $items = $response->json() ?? [];

            if (is_array($items) && count($items)) {
                return $items[0];
            }
        }

        return null;
    }

    /**
     * Kirim task ke device: reboot, factoryReset, setParameterValues, download, dll.
     *
     * $deviceId = _id dari ACS, contoh "E48D8C-hAP%20lite-HG709VJ17JJ"
     * Di path harus di-encode lagi (rawurlencode) supaya "%" jadi "%25"
     * → di server di-decode sekali → balik ke _id asli.
     */
    public function enqueueTask(
        string $deviceId,
        array $task,
        bool $connectionRequest = true,
        int $timeout = 3000
    ): array {
        // "E48D8C-hAP%20lite-..." → "E48D8C-hAP%2520lite-..."
        $encodedId = rawurlencode($deviceId);

        $queryParams = [
            'timeout' => $timeout,
        ];

        if ($connectionRequest) {
            // sama seperti curl: ?connection_request
            $queryParams['connection_request'] = '';
        }

        try {
            $response = $this->client()
                ->withQueryParameters($queryParams)
                ->post("/devices/{$encodedId}/tasks", $task);

            return [
                'status'  => $response->status(),
                'body'    => $response->json(),
                'rawBody' => $response->body(),
            ];
        } catch (ConnectionException $e) {
            return [
                'status'  => 0,
                'body'    => null,
                'rawBody' => $e->getMessage(),
            ];
        } catch (\Throwable $e) {
            return [
                'status'  => 0,
                'body'    => null,
                'rawBody' => $e->getMessage(),
            ];
        }
    }

    /**
     * Task helper: reboot.
     */
    public function rebootDevice(string $deviceId): array
    {
        return $this->enqueueTask($deviceId, ['name' => 'reboot']);
    }

    /**
     * Task helper: factory reset.
     */
    public function factoryResetDevice(string $deviceId): array
    {
        return $this->enqueueTask($deviceId, ['name' => 'factoryReset']);
    }

    /**
     * Task helper: set WiFi SSID/password.
     */
    public function setWifi(
        string $deviceId,
        string $ssid,
        ?string $password = null,
        array $options = []
    ): array {
        $ssidParam     = $options['ssid_param']
            ?? 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.SSID';

        $passwordParam = $options['password_param']
            ?? 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.PreSharedKey.1.PreSharedKey';

        $params = [
            [$ssidParam, $ssid, 'xsd:string'],
        ];

        if ($password !== null && $password !== '') {
            $params[] = [$passwordParam, $password, 'xsd:string'];
        }

        return $this->enqueueTask($deviceId, [
            'name'            => 'setParameterValues',
            'parameterValues' => $params,
        ]);
    }

    /**
     * Task helper: download firmware/file.
     */
    public function downloadFirmware(string $deviceId, string $fileName, array $options = []): array
    {
        $task = [
            'name' => 'download',
            'file' => $fileName,
        ];

        if (!empty($options['fileType'])) {
            $task['fileType'] = $options['fileType'];
        }
        if (!empty($options['targetFileName'])) {
            $task['targetFileName'] = $options['targetFileName'];
        }
        if (!empty($options['username'])) {
            $task['username'] = $options['username'];
        }
        if (!empty($options['password'])) {
            $task['password'] = $options['password'];
        }

        return $this->enqueueTask($deviceId, $task);
    }
}
