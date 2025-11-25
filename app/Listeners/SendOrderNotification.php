<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Jobs\SendOrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderNotification implements ShouldQueue
{
    public function handle(OrderCreated $event): void
    {
        SendOrderCreatedNotification::dispatch($event->order);
    }
}
