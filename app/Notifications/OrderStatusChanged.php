<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class OrderStatusChanged extends Notification
{
    use Queueable;

    public $order;
    public $pdf;
    protected $ccEmails;
    /**
     * Create a new notification instance.
     */
    public function __construct($order, $pdf, $ccEmails = [])
    {
        $this->order = $order;
        $this->pdf = $pdf;
        $this->ccEmails = $ccEmails;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Order Status ' . $this->order->payment->status)
            ->line('The status of your order has been updated to ' . $this->order->payment->status)
            ->action('View Order', url('/orders/' . $this->order->id))
            ->line('Thank you for shopping with us!')
            ->salutation('Admin, Ecommerce')
            ->attachData($this->pdf, 'invoice_order_' . $this->order->id . '.pdf', [
                'mime' => 'application/pdf',
            ]);

        if (!empty($this->ccEmails)) {
            $mail->cc($this->ccEmails);
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'status' => $this->order->payment->status,
            'message' => "Order #{$this->order->id} status has been updated to {$this->order->payment->status}.",
        ];
    }
}
