<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Addinvoice extends Notification
{
    use Queueable;
    private $invoic_id;
    /**
     * Create a new notification instance.
     */
    public function __construct($invoic_id)
    {
        $this->invoic_id = $invoic_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        $url='http://127.0.0.1:8000/InvoicesDetails/'. $this->invoic_id;

        return (new MailMessage)
                    ->subject('اضافة فتورة جديدة')
                    ->line('اضافة فتورة جديدة')
                    ->action('عرض فاتورة', $url)
                    ->line('شكرا لاستخدامك تطبيقنا الخاص بادارة الفواتير');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
