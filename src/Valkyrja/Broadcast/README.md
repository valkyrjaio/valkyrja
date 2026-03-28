# Broadcast

## Introduction

The Broadcast component sends real-time events to channels
via [Pusher](https://pusher.com/). An encrypted variant, a log implementation
for development, and a null implementation for testing are included alongside
the default Pusher backend.

## The Broadcaster Contract

`Valkyrja\Broadcast\Contract\BroadcasterContract` defines a single method:

```php
public function send(MessageContract $message): void;
```

## Messages

A broadcast message carries the channel name, event name, event data, and an
optional text message. Build one with the fluent immutable API on
`Valkyrja\Broadcast\Data\Message`:

```php
use Valkyrja\Broadcast\Data\Message;

$message = (new Message())
    ->withChannel('orders')
    ->withEvent('order.placed')
    ->withData(['order_id' => 42, 'total' => '99.00'])
    ->withMessage('A new order was placed.');
```

`MessageContract` methods:

```php
public function getChannel(): string;
public function withChannel(string $channel): static;
public function getEvent(): string;
public function withEvent(string $event): static;
public function getData(): array;
public function withData(array $data): static;
public function getMessage(): string;
public function withMessage(string $message): static;
```

## Implementations

| Class                    | Description                                         |
|:-------------------------|:----------------------------------------------------|
| `PusherBroadcaster`      | Sends events to Pusher                              |
| `CryptPusherBroadcaster` | Encrypts the payload before sending to Pusher       |
| `LogBroadcaster`         | Logs broadcast events; useful for local development |
| `NullBroadcaster`        | No-op; discards all messages silently               |

The active implementation is resolved from the container as
`BroadcasterContract`. Configure the default via your `Env` class.

## Configuration

| Env Constant                    | Default                    | Description                                   |
|:--------------------------------|:---------------------------|:----------------------------------------------|
| `BROADCAST_DEFAULT_BROADCASTER` | `PusherBroadcaster::class` | Implementation bound to `BroadcasterContract` |
| `BROADCAST_PUSHER_KEY`          | `'pusher-key'`             | Pusher application key                        |
| `BROADCAST_PUSHER_SECRET`       | `'pusher-secret'`          | Pusher application secret                     |
| `BROADCAST_PUSHER_ID`           | `'pusher-id'`              | Pusher application ID                         |
| `BROADCAST_PUSHER_CLUSTER`      | `'us1'`                    | Pusher cluster region                         |
| `BROADCAST_PUSHER_USE_TLS`      | `true`                     | Whether to use TLS for Pusher connections     |
| `BROADCAST_LOG_LOGGER`          | `LoggerContract::class`    | Logger used by `LogBroadcaster`               |

## Service Registration

The Broadcast service provider registers the following singletons:

| Contract / Class         | Description                                       |
|:-------------------------|:--------------------------------------------------|
| `BroadcasterContract`    | Active broadcaster (default: `PusherBroadcaster`) |
| `PusherBroadcaster`      | Pusher implementation                             |
| `CryptPusherBroadcaster` | Encrypted Pusher implementation                   |
| `LogBroadcaster`         | Log implementation                                |
| `NullBroadcaster`        | No-op implementation                              |
| `Pusher`                 | Configured Pusher SDK instance                    |