<?php

namespace App\Providers;

use App\Events\OrderCreated;
use App\Listeners\SendOrderNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderCreated::class => [
            SendOrderNotification::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
