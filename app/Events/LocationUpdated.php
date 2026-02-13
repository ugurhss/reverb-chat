<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LocationUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $device_id,
        public float $lat,
        public float $lng,
        public ?float $accuracy,
        public string $recorded_at,
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('locations');
    }

    public function broadcastAs(): string
    {
        return 'location.updated';
    }
}

