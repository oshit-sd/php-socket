<?php

namespace Oshitsd\PhpSocket\Services;

use WebSocket\Client;
use Exception;
use Oshitsd\PhpSocket\Contracts\SocketInterface;

class SocketService implements SocketInterface
{
    private string $host;
    private string $env;
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
        $this->env = config('phpsocket.env');
        $this->apiKey = config('phpsocket.api_key');

        $protocol = $this->env == 'production' ? 'wss' : 'ws';
        $this->url = "{$protocol}://{$this->host}/socket.io/?EIO=4&transport=websocket";
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
            $initialResponse = $this->client->receive();

            $authInfo = [
                "apiKey" => $this->apiKey,
                "userId" => $authData['userId'] ?? null,
                "userName" => $authData['userName'] ?? null,
            ];

            // Format message with authentication info
            $this->client->send('40' . json_encode($authInfo));
            usleep(500000); // delay for 0.5 seconds or 500ms

            // Receive the authentication response
            $authResponse = $this->client->receive();
            if (strpos($authResponse, 'Authentication error') !== false) {

                $jsonAuthResponse = substr($authResponse, 2);
                $authResponseMessage = json_decode($jsonAuthResponse, true);

                return [
                    'status' => 'error',
                    'message' => $authResponseMessage['message'] ?? 'Authentication failed.',
                ];
            }

            $this->connected = true;
            return [
                'status' => 'success',
                'message' => 'Connected to socket server successfully.',
                'data' => [
                    'host' => $this->host
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
                'message' => 'Connection not established.',
            ];
        }

        try {
            $message = '42["message", ' . json_encode($payload) . ']';
            $this->client->send($message);

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
            $receiveAck = $this->client->receive();
            $this->client->close();

            return [
                'status' => 'success',
                'message' => 'Acknowledgment received successfully.',
                'data' => json_decode(substr($receiveAck, 2), true)
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
