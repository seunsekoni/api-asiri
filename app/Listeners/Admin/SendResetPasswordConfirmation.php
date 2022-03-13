<?php

namespace App\Listeners\Admin;

use App\Events\Admin\ResetPassword;
use App\Notifications\Admin\PasswordResetConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendResetPasswordConfirmation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ResetPassword $event)
    {
        $event->admin->notify(new PasswordResetConfirmed($event->callbackContactUrl));
    }
}
