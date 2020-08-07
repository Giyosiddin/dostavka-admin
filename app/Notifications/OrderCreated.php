<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\Telegram\TelegramFile;
use Illuminate\Support\Facades\Log;
use App\Order;

class OrderCreated extends Notification
{
    use Queueable;

      public $Order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
         return [TelegramChannel::class];
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

      public function toTelegram($notifiable)
    {
        // $order = $this->order;
        $order = Order::find(9);
        $products = $order->products;
        $order_products = "";
        foreach($products as $product){
            $order_products .= "\n\n Maxsulot ID: ".$product->id."\n Maxsulot nomi: ".$product->title."\n Soni: *".$product->pivot->quantity."*";
        }
        Log::Info($order_products); 
        return TelegramMessage::create()
            // Optional recipient user id.
            ->to('-431597365')
            // Markdown supported.
            ->content("*Buyurtma:* ".$order->id."\n*Mijoz:* ".$order->name."\n*Telefon raqami:* ".$order->phone."\n*To'lov turi:* ".$order->payment_type."\n*Buyurtma summasi:*".$order->overal." \n*Maxsulotlar:* ".$order_products);
    }

    /**
     * Route notifications for the Telegram channel.
     *
     * @return int
     */
    public function routeNotificationForTelegram()
    {
        return $this->telegram_user_id;
    }
}
