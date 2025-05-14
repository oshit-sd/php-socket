<?php

namespace Oshitsd\PhpSocket\Services;

use WebSocket\Client;
use Exception;
use Oshitsd\PhpSocket\Contracts\SocketInterface;

class SocketService implements SocketInterface
{
    private string $host;
    private int $port;
    private string $apiKey;
    private string $url;
    private ?Client $client = null;
    private bool $connected = false;

    /**
     * SocketService constructor.
     * Initializes configuration and constructs the WebSocket URL.
     */
    public function __construct()
    {
        $this->host = config('phpsocket.host');
        $this->port = config('phpsocket.port');
        $this->apiKey = config('phpsocket.api_key');

        $this->url = "ws://{$this->host}/socket.io/?EIO=4&transport=websocket";

        if ($this->port) {
            $this->url = "ws://{$this->host}:{$this->port}/socket.io/?EIO=4&transport=websocket";
        }
    }

    /**
     * Establish a connection to the WebSocket server.
     *
     * @param array $authData Authentication data
     * @return array The connection response including status and message.
     */
    public function connect(array $authData = []): array
    {
        try {
            $this->client = new Client($this->url, ['timeout' => 20]);
            $authInfo = [
                "apiKey" => $this->apiKey,
                "userId" => $authData['userId'] ?? null,
                "userName" => $authData['userName'] ?? null,
            ];

            // Format message with authentication info
            $this->client->send('40' . json_encode($authInfo));
            usleep(500000); // delay for 0.5 seconds or 500ms
            $this->connected = true;

            return [
                'status' => 'success',
                'message' => 'Connected to socket server successfully.',
                'data' => [
                    'url' => "ws://{$this->host}:{$this->port}"
                ]
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Connection failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send a message to the WebSocket server.
     *
     * @param array $payload Data to send.
     * @return array The response including status and message.
     */
    public function send(array $payload): array
    {
        if (!$this->isConnected()) {
            return [
                'status' => 'error',
                'message' => 'Not connected. Call connect() first.',
            ];
        }

        try {
            $message = '42["message", ' . json_encode($payload) . ']';
            $this->client->send($message);
            $this->client->close();

            return [
                'status' => 'success',
                'message' => 'Message sent successfully.',
                'data' => $payload
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Send failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Receive acknowledgment from the WebSocket server.
     *
     * @return array The acknowledgment response including status and message.
     */
    public function receiveAck(): array
    {
        if (!$this->isConnected()) {
            return [
                'status' => 'error',
                'message' => 'Connection not established.',
            ];
        }

        try {
            $msg = $this->client->receive();
            $json = substr($msg, 2);
            $data = json_decode($json, true);

            return [
                'status' => 'success',
                'message' => 'Acknowledgment received successfully.',
                'data' => $data
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to receive acknowledgment: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Close the WebSocket connection.
     *
     * @return array The response including status and message.
     */
    public function close(): array
    {
        if ($this->client) {
            $this->client->close();
            $this->connected = false;

            return [
                'status' => 'success',
                'message' => 'Connection closed successfully.',
            ];
        }

        return [
            'status' => 'error',
            'message' => 'No active connection to close.',
        ];
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
