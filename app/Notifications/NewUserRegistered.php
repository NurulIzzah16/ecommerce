<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\User;

class NewUserRegistered extends Notification
{
    use Queueable;
    public $user;
    protected $ccEmails;
    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, $ccEmails = [])
    {
        $this->user = $user;
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
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
                    ->subject('New User Registered')
                    ->greeting('Hello Admin,')
                    ->line('A new user has registered with the email ' . $this->user->email)
                    ->action('View User', url('/users/' . $this->user->id))
                    ->line('Thank you for using our application!')
                    ->salutation('Admin, Ecommerce');
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
            'user_id' => $this->user->id,
            'name' => $this->user->username,
            'message' => "User baru dengan nama {$this->user->username} telah mendaftar.",
        ];
    }
}
