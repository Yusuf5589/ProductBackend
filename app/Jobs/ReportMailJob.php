<?php

namespace App\Jobs;

use App\Mail\ReportMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ReportMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productNumber, $userNumber;
    public function __construct($productNumber, $userNumber)
    {
        $this->productNumber = $productNumber;
        $this->userNumber = $userNumber;
    }


    public function handle(): void
    {
        Mail::to("yusufakapkiner@gmail.com")->send(new ReportMail($this->productNumber, $this->userNumber));
    }
}
