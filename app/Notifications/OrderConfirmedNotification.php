<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmedNotification extends Notification
{
    public function __construct(private readonly Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->order->loadMissing('orderItems.gameAd.game');

        return (new MailMessage)
            ->from(config('mail.from.address'), 'GameLink No Reply')
            ->subject('Tu pedido ha sido confirmado - GameLink')
            ->view('emails.orders.confirmed', [
                'user' => $notifiable,
                'order' => $this->order,
                'ordersUrl' => route('orders.index'),
                'supportEmail' => config('mail.from.address'),
            ]);
    }
}
