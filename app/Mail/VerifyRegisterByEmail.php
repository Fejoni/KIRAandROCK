<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyRegisterByEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $verify_link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($aData)
    {
        $this->name = $aData['name'];
        $this->verify_link = $aData['verify_link'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.verify_register_by_email');
    }
}
