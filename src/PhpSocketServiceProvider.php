<?php

namespace Oshitsd\PhpSocket;

use Illuminate\Support\ServiceProvider;
use Oshitsd\PhpSocket\Contracts\SocketInterface;
use Oshitsd\PhpSocket\Services\SocketService;

class PhpSocketServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/phpsocket.php', 'phpsocket');

        $this->app->singleton('phpsocket', function () {
            return new SocketService();
        });

        $this->app->singleton(SocketInterface::class);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/phpsocket.php' => config_path('phpsocket.php'),
        ], 'phpsocket-config');
    }
}
