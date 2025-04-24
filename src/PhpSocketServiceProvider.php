<?php

namespace Oshitsd\PhpSocket;

use Illuminate\Support\ServiceProvider;
use Oshitsd\PhpSocket\Contracts\SocketInterface;
use Oshitsd\PhpSocket\Services\SocketService;

class PhpSocketServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/php-socket.php', 'php-socket');

        $this->app->singleton('php-socket', function () {
            return new SocketService();
        });

        $this->app->singleton(SocketInterface::class);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/php-socket.php' => config_path('php-socket.php'),
        ], 'config');
    }
}
