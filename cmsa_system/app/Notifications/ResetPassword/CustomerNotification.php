<?php

namespace App\Notifications\ResetPassword;

use App\Mail\ResetPassword\CustomerMail;
use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class CustomerNotification extends BaseResetPassword
{
    use Queueable;

    /**
     * Get the notification's channels.
     * mail / database / broadcast / nexmo / slack
     * @param $notifiable
     *
     * @return string[]
     */
    public function via($notifiable): array {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     * @param mixed $notifiable
     *
     * @return CustomerMail|MailMessage|mixed
     */
    public function toMail($notifiable): mixed {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        $reset_url = route('front.password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset()
            ]);
        $to = $notifiable->getEmailForPasswordReset();

        return (new CustomerMail($reset_url))->to($to);
    }
}
