<?php

namespace App\Listeners;

use App\Events\contactEvent;
use App\Notifications\ContactCreatedNotification;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ContactListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(contactEvent $event): void
    {
        // dd($event);
        $user = auth()->user();
        $user->notify(new ContactCreatedNotification($event->contact));
    }
}
