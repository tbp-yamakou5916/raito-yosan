<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommonMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $items;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($items)
    {
        $this->items = $items;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->to($this->items['to']);
        if(isset($this->items['cc'])) $this->cc($this->items['cc']);
        if(isset($this->items['bcc'])) $this->bcc($this->items['bcc']);
        if(isset($this->items['replyTo'])) $this->replyTo($this->items['replyTo']);
        if(isset($this->items['from'])) $this->from($this->items['from']);
        return $this
            ->text($this->items['view'], $this->items['params'])
            ->subject($this->items['subject']);
    }
}
