<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\InvoicePaid;
use NotificationChannels\Telegram\TelegramChannel;
use Notification;
use App\Events\TelegramSend;
use Illuminate\Support\Facades\Log;

class SendTelegramNotification
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
     * @param  TelegramSend $event
     * @return void
     */
    public function handle(TelegramSend $event)
    {       

          Notification::route('telegram', '-420527890')
            ->notify(new InvoicePaid($event->product));
    }
}
