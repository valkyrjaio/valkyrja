# Crypt

## Introduction

The Crypt component provides symmetric authenticated encryption and decryption
using PHP's libsodium extension. It supports encrypting plain strings, arrays,
and objects, and includes a null implementation for testing.

The default implementation uses `sodium_crypto_secretbox`, which provides
authenticated encryption with a MAC. The key is stored as a hex string in the
application configuration and is converted to raw bytes at runtime. Keys are
zeroed from memory after use via `sodium_memzero`.

## The CryptContract

`Valkyrja\Crypt\Manager\Contract\CryptContract` defines the full encryption API:

```php
// Validate
public function isValidEncryptedMessage(string $encrypted): bool;

// Strings
public function encrypt(string $message, string|null $key = null): string;
public function decrypt(string $encrypted, string|null $key = null): string;

// Arrays
public function encryptArray(array $array, string|null $key = null): string;
public function decryptArray(string $encrypted, string|null $key = null): array;

// Objects
public function encryptObject(object $object, string|null $key = null): string;
public function decryptObject(string $encrypted, string|null $key = null): object;
```

All `$key` parameters accept `#[SensitiveParameter]` values. When `null`, the
key configured in the application is used. All methods throw `CryptException` on
failure.

`isValidEncryptedMessage()` returns `false` rather than throwing when the
message is invalid.

## Encryption Details

`SodiumCrypt` uses the following process:

1. A random nonce is generated using
   `random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES)`.
2. The message is encrypted with
   `sodium_crypto_secretbox($message, $nonce, $key)`.
3. The nonce is prepended to the ciphertext and the result is hex-encoded with
   `bin2hex`.
4. `sodium_memzero()` is called on the plaintext message and key bytes
   immediately after use.

Decryption reverses the process: `hex2bin` decodes the ciphertext, the nonce is
extracted from the leading bytes, and `sodium_crypto_secretbox_open`
authenticates and decrypts.

Array and object variants serialize the value to JSON before encrypting and
deserialize after decrypting.

## Implementations

| Class         | Description                                      |
|:--------------|:-------------------------------------------------|
| `SodiumCrypt` | Authenticated encryption via libsodium secretbox |
| `NullCrypt`   | No-op; returns input unchanged (for testing)     |

The active implementation is resolved from the container as `CryptContract`.
Configure the default via your `Env` class.

## Configuration

| Env Constant    | Default              | Description                             |
|:----------------|:---------------------|:----------------------------------------|
| `CRYPT_DEFAULT` | `SodiumCrypt::class` | Implementation bound to `CryptContract` |

The encryption key itself is read from `Config::$key` (the application-level
key). It must be a hex-encoded string representing the raw key bytes expected by
libsodium.

## Service Registration

The Crypt service provider registers the following singletons:

| Contract / Class | Description                                    |
|:-----------------|:-----------------------------------------------|
| `CryptContract`  | Active implementation (default: `SodiumCrypt`) |
| `SodiumCrypt`    | libsodium secretbox implementation             |
| `NullCrypt`      | No-op implementation                           |