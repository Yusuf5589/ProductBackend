<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailSend extends Mailable
{
    use Queueable, SerializesModels;

    private $email;
    private $username;
    public function __construct($email,$username)
    {
        $this->username = $username;
        $this->email = $email;
    }

    public function build(){
        return $this->view("Mail")->subject("Hoşgeldiniz")->with(["username"=>$this->username, "email"=>$this->email]);
    }
}
