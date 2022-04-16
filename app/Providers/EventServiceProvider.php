<?php

namespace App\Providers;

use App\Listeners\SendMail;
use App\Listeners\SendSoket;
use App\Events\SubscriptionCreated;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        SubscriptionCreated::class => [
            SendMail::class,
            SendSoket::class
        ],

    ];



    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
