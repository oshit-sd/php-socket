<?php

namespace Oshitsd\PhpSocket\Services;

use WebSocket\Client;
use Exception;
use Oshitsd\PhpSocket\Contracts\SocketInterface;

class SocketService implements SocketInterface
{
    private string $host;
    private int $port;
    private string $url;
    private ?Client $client = null;
    private bool $connected = false;

    /**
     * SocketService constructor.
     * Initializes configuration and constructs the WebSocket URL.
     */
    public function __construct()
    {
        $this->host = config('php-socket.host');
        $this->port = config('php-socket.port');
        $this->url = "ws://{$this->host}:{$this->port}/socket.io/?EIO=4&transport=websocket";
    }

    /**
     * Establish a connection to the WebSocket server.
     *
     * @return string Success or error message.
     */
    public function connect(): string
    {
        try {
            $this->client = new Client($this->url, ['timeout' => 20]);
            $this->client->send('40{"apiKey":"riseuplabs123"}'); // Custom handshake
            usleep(500000); // delay for 0.5 seconds or 500ms
            $this->connected = true;

            return "✅ Connected to socket server successfully.";
        } catch (Exception $e) {
            return "❌ Connection failed: " . $e->getMessage();
        }
    }

    /**
     * Send a message to the WebSocket server.
     *
     * @param array $payload Data to send.
     * @return string Success or error message.
     */
    public function send(array $payload): string
    {
        if (!$this->isConnected()) {
            return "⚠️ Not connected. Call connect() first.";
        }

        try {
            $message = '42["message", ' . json_encode($payload) . ']';
            $this->client->send($message);
            $this->client->close();

            return "📤 Message sent.";
        } catch (Exception $e) {
            return "❌ Send failed: " . $e->getMessage();
        }
    }

    /**
     * Receive a message from the WebSocket server.
     *
     * @return string The received message or an error message.
     */
    public function receive(): string
    {
        if (!$this->isConnected()) {
            return "⚠️ Not connected.";
        }

        try {
            $msg = $this->client->receive();
            return "📨 Received: $msg";
        } catch (Exception $e) {
            return "❌ Receive failed: " . $e->getMessage();
        }
    }

    /**
     * Close the WebSocket connection.
     *
     * @return string Success or warning message.
     */
    public function close(): string
    {
        if ($this->client) {
            $this->client->close();
            $this->connected = false;

            return "🔒 Connection closed.";
        }

        return "⚠️ No active connection.";
    }

    /**
     * Check if the connection is active.
     *
     * @return bool True if connected, false otherwise.
     */
    private function isConnected(): bool
    {
        return $this->connected && $this->client !== null;
    }
}
