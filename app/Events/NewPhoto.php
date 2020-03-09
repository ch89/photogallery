<?php

namespace App\Events;

use App\Photo;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewPhoto implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $photo;

    public function __construct(Photo $photo) {
        $this->photo = $photo;
    }

    public function broadcastOn() {
        return new Channel("photos");
    }
}