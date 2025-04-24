# 📡 PhpSocket

**PhpSocket** is a Laravel wrapper for handling Socket.IO WebSocket communication using a simple, expressive API. Built on top of the [`textalk/websocket`](https://github.com/Textalk/websocket) client, it allows your Laravel app to communicate easily with a Socket.IO server.

---

## 🚀 Features

- Connect to a Socket.IO WebSocket server
- Send and receive messages
- Built-in support for custom payloads and events
- Laravel Facade for simple usage
- Configurable host and port

---

## 🧰 Requirements

- PHP 8.0 or higher
- Laravel 9, 10, 11, 12 or upper
- WebSocket server with Socket.IO (EIO=4) support

---

## 📦 Installation

Install via Composer:

```bash
composer require oshitsd/php-socket
```

Publish the config file:

```bash
php artisan vendor:publish --tag=config
```

This will create a `config/php-socket.php` file where you can configure the WebSocket host and port.

---

## ⚙️ Configuration

In your `.env` file, add:

```env
SOCKET_HOST=127.0.0.1
SOCKET_PORT=3000
```

Or modify the `config/php-socket.php` file directly.

---

## 🧪 Basic Usage

### Connect to Socket

```php
use PhpSocket;

PhpSocket::connect();
```

### Send a Message

```php
PhpSocket::send([
    "event" => "LARA_NOTIFY",
    "to" => "all",
    "message" => [
        "time" => date('Y-m-d H:i:s'),
        "text" => "Laravel says hi 👋",
        "user" => [
            "id" => 1,
            "name" => "OSHIT SUTRA DAR"
        ]
    ]
]);
```

### Receive a Message

```php
$response = PhpSocket::receive();
```

### Close the Connection

```php
PhpSocket::close();
```

---

## 🧼 Example Output

```bash
👋 Connected to socket server successfully.
📤 Message sent.
📨 Received: {message}
🔒 Connection closed.
```

---

## 🧪 Testing

You can run the package tests with:

```bash
./vendor/bin/phpunit
```

Tests are located in the `tests/` directory.

---

## 📄 License

This package is open-source software licensed under the [MIT license](LICENSE).

---

## 🙌 Credits

Developed by [Oshitsd](https://github.com/oshitsd)  
WebSocket client powered by [textalk/websocket](https://github.com/Textalk/websocket)

