<?php

namespace App\Events;

use App\Models\Prospect;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProspectStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Prospect $prospect)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('prospects'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'status-changed';
    }

    public function broadcastWith(): array
    {
        return [
            'id'     => $this->prospect->id,
            'status' => $this->prospect->status,
            'label'  => $this->prospect->statusLabel(),
            'color'  => $this->prospect->statusColor(),
        ];
    }
}
