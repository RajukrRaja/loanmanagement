<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PermissionsUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $role;

    public function __construct(string $role)
    {
        $this->role = $role;
    }

    public function broadcastOn()
    {
        return new Channel('permissions');
    }

    public function broadcastAs()
    {
        return 'permissions.updated';
    }

    public function broadcastWith()
    {
        return ['role' => $this->role];
    }
}