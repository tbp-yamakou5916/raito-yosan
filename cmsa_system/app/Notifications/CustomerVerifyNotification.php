<?php

namespace App\Notifications;

use App\Mail\ResetPassword\CustomerMail;
use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class CustomerVerifyNotification extends BaseResetPassword
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
        $myActionText = __('Verify Email Address');
        // $myActionText = "Verify Email Address"
        $myActionUrl = $this->verificationUrl($notifiable);
        $mailMessage = (new MailMessage)
            ->subject(__('【初回】ご登録ありがとうございます'))
//            ->view('emails.verify', ['myActionUrl' => $myActionUrl])
// HTMLメール化したい場合はこのコメントを外す  resources/views/emails.verify.blade.phpの内容がHTMLメールで送られる
            ->action($myActionText, $myActionUrl);
        return $mailMessage;
    }
}
