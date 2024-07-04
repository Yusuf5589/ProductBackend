<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgotPassMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    private $email;
    private $token;
    public function __construct($email, $token)
    {
        $this->email = $email;
        $this->token = $token;
    }

    public function build(){
        return $this->view("ForgotMailPage")->subject("Şifre Değiştir")->with(["token"=>$this->token, "email"=>$this->email]);
    }
}
