<?php

namespace App\Http\Controllers;

use App\Events\LocationUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'device_id' => ['required', 'string', 'max:64'],
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'accuracy' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'recorded_at' => ['nullable', 'date'],
        ]);

        $recordedAt = isset($data['recorded_at'])
            ? Carbon::parse($data['recorded_at'])->toIso8601String()
            : now()->toIso8601String();

        broadcast(new LocationUpdated(
            device_id: $data['device_id'],
            lat: (float) $data['lat'],
            lng: (float) $data['lng'],
            accuracy: isset($data['accuracy']) ? (float) $data['accuracy'] : null,
            recorded_at: $recordedAt,
        ))->toOthers();

        return response()->json(['ok' => true]);
    }
}
