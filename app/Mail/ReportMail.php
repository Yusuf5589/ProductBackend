<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $productNumber, $userNumber;
    public function __construct($productNumber, $userNumber)
    {
        $this->productNumber = $productNumber;
        $this->userNumber = $userNumber;
    }

    public function build(){
        return $this->view('Reports')->subject("Raporlarınız")->with(["productNumber"=> $this->productNumber, "userNumber" => $this->userNumber]);
    }
}
