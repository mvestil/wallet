<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\WalletCredited' => [
            'App\Listeners\SendCreditEmailNotification',
            'App\Listeners\SendCreditSMSNotification',
        ],
        'App\Events\WalletDebited' => [
            'App\Listeners\SendDebitEmailNotification',
            'App\Listeners\SendDebitSMSNotification',
            'App\Listeners\NotifyAdminForHugeWithdrawal',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
