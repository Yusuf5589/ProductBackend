<?php

use App\Jobs\ReportMail;
use App\Jobs\ReportMailJob;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
    })->withSchedule(function(Schedule $schedule) {
        $schedule->call(function(){
            $productNumber = Product::count();
            $userNumber = User::count();
            ReportMailJob::dispatch($productNumber, $userNumber);
        })->dailyAt('22:00');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();



    