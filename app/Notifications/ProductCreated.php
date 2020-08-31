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
use Illuminate\Support\Facades\Storage;
use App\Product;

class ProductCreated extends Notification
{
    use Queueable;

    public $product;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($product)
    {
        $this->product = $product;
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
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

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
        $product_url = url('http://parkent.online/detail/'.$this->product->id);
        $url = "https://t.me/parkent_online";
        $tovar = Product::find($this->product->id);
        $media = $tovar->getMedia('gallary');
        $img_url = $media[0]->getPath();
        $tag_str = $this->product->category->slug;
        $tag = str_replace('-', '\_', $tag_str);
        $title_arr = explode(' ', $this->product->title);
        // return Log::info($title_arr);
        if(isset($title_arr[1])){
            $title_tag = $title_arr[0] ."\_". $title_arr[1];
        }else{
            $title_tag = $title_arr[0];
        }


        return TelegramFile::create()
            // Optional recipient user id.
            ->to('-1001443336373')
            // Markdown supported.
            ->content($this->product->title."\n\n".$this->product->description."\n\n💰 Narxi: *".$this->product->cost." so'm* \n\nQo'ng'iroq qilib buyurtma bering:\n+998994013937\n\nYetkazib berish bepul\n#" . strtolower($title_tag) . " #". $tag)
            ->file($img_url,'photo') // local photo
            // (Optional) Inline Buttons
            ->button('Online buyurtma', $product_url)
            ->button("Kanalga a'zo bo'lish", $url);
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
