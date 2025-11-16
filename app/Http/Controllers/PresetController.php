<?php

namespace App\Http\Controllers;

use App\Services\GenieAcsClient;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PresetController extends Controller
{
    public function index(GenieAcsClient $client)
    {
        $presets = $client->listPresets();

        return view('presets.index', [
            'presets' => $presets,
        ]);
    }

    public function create()
    {
        return view('presets.edit', [
            'name' => '',
            'preset' => [
                'weight' => 0,
                'precondition' => '',
                'configurations' => [],
            ],
        ]);
    }

    public function store(Request $request, GenieAcsClient $client)
    {
        // $data = $this->validatePreset($request, true);

        // $name = $data['name'];
        // unset($data['name']);

        // $client->savePreset($name, $data);

        // return redirect()->route('presets.index')->with('status', "Preset {$name} disimpan.");
        $data = $request->validate([
            'name'              => 'required|string',
            'weight'            => 'nullable|integer',
            'precondition'      => 'nullable|string',
            'configurations_json' => 'nullable|string',
            'tags_helper'       => 'nullable|string',
        ]);

        // Kalau precondition kosong tapi tags_helper terisi, generate otomatis
        if (empty($data['precondition']) && !empty($data['tags_helper'])) {
            $tags = collect(explode(',', $data['tags_helper']))
                ->map(fn($t) => trim($t))
                ->filter()
                ->values()
                ->all();

            if (count($tags) === 1) {
                $preconditionArray = ['_tags' => $tags[0]];
            } elseif (count($tags) > 1) {
                $preconditionArray = ['_tags' => ['$in' => $tags]];
            } else {
                $preconditionArray = null;
            }

            if ($preconditionArray) {
                $data['precondition'] = json_encode($preconditionArray, JSON_UNESCAPED_SLASHES);
            }
        }
    }

    public function edit(string $name, GenieAcsClient $client)
    {
        $preset = $client->getPreset($name);

        if (!$preset) {
            $preset = [
                'weight' => 0,
                'precondition' => '',
                'configurations' => [],
            ];
        }

        return view('presets.edit', [
            'name' => $name,
            'preset' => $preset,
        ]);
    }

    public function update(string $name, Request $request, GenieAcsClient $client)
    {
        $data = $this->validatePreset($request, false);

        $client->savePreset($name, $data);

        return redirect()->route('presets.index')->with('status', "Preset {$name} diupdate.");
    }

    public function destroy(string $name, GenieAcsClient $client)
    {
        $client->deletePreset($name);

        return redirect()->route('presets.index')->with('status', "Preset {$name} dihapus.");
    }

    protected function validatePreset(Request $request, bool $includeName = true): array
    {
        $rules = [
            'weight' => 'nullable|integer',
            'precondition' => 'nullable|string',
            'configurations_json' => 'nullable|string',
        ];

        if ($includeName) {
            $rules['name'] = 'required|string';
        }

        $data = $request->validate($rules);

        $configJson = $data['configurations_json'] ?? '[]';
        $configurations = [];

        if ($configJson !== '') {
            try {
                $configurations = json_decode($configJson, true, 512, JSON_THROW_ON_ERROR);
            } catch (\Throwable $e) {
                throw ValidationException::withMessages([
                    'configurations_json' => 'JSON configurations tidak valid: ' . $e->getMessage(),
                ]);
            }
        }

        return [
            'name' => $data['name'] ?? '',
            'weight' => (int) ($data['weight'] ?? 0),
            'precondition' => $data['precondition'] ?? '',
            'configurations' => $configurations,
        ];
    }
}
