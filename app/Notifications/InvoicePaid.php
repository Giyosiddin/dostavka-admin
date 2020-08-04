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

class InvoicePaid extends Notification
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
        $tovar = Product::find($this->product->id);
        $url = url('http://parkent.online/');
        $media = $tovar->getFirstMediaUrl('gallary', 'thumb');
        $images = $tovar->getMedia();
        $tag_str = $this->product->category->slug;
        $tag = str_replace('-', '\_', $tag_str);


        //  if (count($images) > 0){
        //     $cover = $images[0];
        // }
        // if ($cover){

        // }
        Log::info($this->product);
        Log::info($images);
        Log::info($tovar);
        Log::debug($media);
        return TelegramFile::create()
            // Optional recipient user id.
            ->to('-420527890')
            // Markdown supported.
            ->content($this->product->title."\n\n💰 Narxi: *".$this->product->cost." so'm* \n\nQo'ng'iroq qilib buyurtma bering:\n\n+998994013937\n".$images." \n\nYetkazib berish bepul\n#" . $tag)
            ->file('http://manage.parkent.online/storage/43/Redmi-Note-9-3.64..jpg', 'photo') // local photo
            // (Optional) Inline Buttons
            ->button('View Invoice', $url)
            ->button('Download Invoice', $url);
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
