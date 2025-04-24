<?php

namespace Oshitsd\PhpSocket\Contracts;

interface SocketInterface
{
    public function connect(): string;
    public function send(array $payload): string;
    public function receive(): string;
    public function close(): string;
}
