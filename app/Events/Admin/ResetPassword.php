<?php

namespace App\Events\Admin;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResetPassword
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Authenticatable $admin;
    public string $callbackContactUrl;

    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $admin
     * @param string $callbackContactUrl
     */
    public function __construct(Authenticatable $admin, string $callbackContactUrl)
    {
        $this->admin = $admin;
        $this->callbackContactUrl = $callbackContactUrl;
    }
}
