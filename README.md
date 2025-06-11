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

- PHP 7.4 or higher
- Laravel 8, 9, 10, 11, 12 or upper
- WebSocket server with Socket.IO (EIO=4) support

---

## 📦 Installation

Install via Composer:

```bash
composer require oshitsd/php-socket
```

Publish the config file:

```bash
php artisan vendor:publish --tag=phpsocket-config
```

This will create a `config/phpsocket.php` file where you can configure the WebSocket host and port.

---

## ⚙️ Configuration

In your `.env` file, add:

```env
PHP_SOCKET_ENV=production
PHP_SOCKET_HOST=127.0.0.1
PHP_SOCKET_API_KEY=
```

Or modify the `config/phpsocket.php` file directly.


---

# 🧪 Basic Usage

## 🔌 Connect to Socket

### Without Authentication

```php
use PhpSocket;

PhpSocket::connect();
```

### With Authentication

```php
use PhpSocket;

$user = [
    "userId" => 1,
    "userName" => "OSHIT SUTRA DAR"
];

$connect = PhpSocket::connect($user);
```

---

## 📤 Send a Message

```php
$response = PhpSocket::send([
    "event" => "LARA_NOTIFY",
    "to" => "all", // all | user_id
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

---

## 📩 Receive Message Response

```php
$response = PhpSocket::receiveAck();
```

---

## 🔒 Close the Connection

```php
$close = PhpSocket::close();
```

---

## 🧼 Example Output

```bash
👋 Connected to socket server successfully.
📤 Message sent.
📨 Received: {message response}
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

Developed by [OSHIT SD](https://github.com/oshit-sd)  
WebSocket client powered by [textalk/websocket](https://github.com/Textalk/websocket)


![Packagist Version](https://img.shields.io/packagist/v/oshitsd/php-socket)