<?php

namespace App\Mail\ResetPassword;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $reset_url;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reset_url)
    {
        $this->reset_url = $reset_url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject(__('mail.reset_password.customer.title'))
            ->text('mails.customer.reset_password')
             ->with('reset_url', $this->reset_url);

        return $this;
    }
}
