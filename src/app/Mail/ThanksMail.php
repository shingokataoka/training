<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ThanksMail extends Mailable
{
    use Queueable, SerializesModels;

    public $items;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($items, $user)
    {
        $this->items = $items;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.thanks')
            ->subject('ご購入ありがとうございます。');
    }
}
