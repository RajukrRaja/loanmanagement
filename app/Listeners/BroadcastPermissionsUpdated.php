<?php

namespace App\Listeners;

use App\Events\PermissionsUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BroadcastPermissionsUpdated implements ShouldQueue
{
    public function handle(PermissionsUpdated $event)
    {
        // Broadcasting is handled by the event's ShouldBroadcast implementation
    }
}