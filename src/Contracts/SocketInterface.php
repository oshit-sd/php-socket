<?php

namespace Oshitsd\PhpSocket\Contracts;

interface SocketInterface
{
    /**
     * Establishes a connection to the WebSocket server.
     * 
     * @param array $authData Authentication data
     * @return array Connection result, including status and message.
     */
    public function connect(array $authData = []): array;

    /**
     * Sends a message to the WebSocket server.
     * 
     * @param array $payload Message to send.
     * @return array Send result, including status and message.
     */
    public function send(array $payload): array;

    /**
     * Receives an acknowledgment from the WebSocket server.
     * 
     * @return array Acknowledgment result, including status, message, and data.
     */
    public function receiveAck(): array;

    /**
     * Closes the WebSocket connection.
     * 
     * @return array Close result, including status and message.
     */
    public function close(): array;
}
