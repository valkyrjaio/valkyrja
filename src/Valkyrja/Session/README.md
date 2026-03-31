# Session

## Introduction

The Session component manages user session data with support for PHP's native
session mechanism, cache-backed storage, cookie sessions, JWT sessions, token
sessions, and encrypted variants of each. CSRF token generation and validation
are built in.

## The Session Contract

`Valkyrja\Session\Contract\SessionContract` defines the full session API:

```php
// Lifecycle
public function start(): void;
public function isActive(): bool;
public function clear(): void;
public function destroy(): void;

// Identity
public function getId(): string;
public function setId(string $id): void;
public function getName(): string;
public function setName(string $name): void;

// Data access
public function has(string $id): bool;
public function get(string $id, mixed $default = null): mixed;
public function set(string $id, mixed $value): void;
public function remove(string $id): bool;
public function all(): array;

// CSRF
public function generateCsrfToken(string $id): string;
public function validateCsrfToken(string $id, string $token): void;  // throws on invalid
public function isCsrfTokenValid(string $id, string $token): bool;
```

### CSRF Tokens

`generateCsrfToken()` creates a 128-character hexadecimal token from 64
cryptographically random bytes and stores it in the session under the given ID.
`validateCsrfToken()` compares the stored token against the provided one using
`hash_equals()` for timing-safe comparison and throws `InvalidCsrfToken` on
mismatch. `isCsrfTokenValid()` returns a boolean instead of throwing.

## Implementations

Valkyrja ships with a wide range of session backends to cover both HTTP and CLI
contexts:

### HTTP Sessions

| Class                         | Description                                            |
|:------------------------------|:-------------------------------------------------------|
| `PhpSession`                  | PHP native `$_SESSION` with configurable cookie params |
| `CookieSession`               | Session data stored in an HTTP cookie                  |
| `EncryptedCookieSession`      | Cookie session with encrypted payload                  |
| `HeaderJwtSession`            | Session stored as a JWT in the `Authorization` header  |
| `EncryptedHeaderJwtSession`   | JWT header session with encrypted payload              |
| `HeaderTokenSession`          | Session stored as a plain token in an HTTP header      |
| `EncryptedHeaderTokenSession` | Token header session with encrypted payload            |

### CLI Sessions

| Class                         | Description                                     |
|:------------------------------|:------------------------------------------------|
| `OptionJwtSession`            | Session stored as a JWT in a CLI option         |
| `EncryptedOptionJwtSession`   | JWT CLI session with encrypted payload          |
| `OptionTokenSession`          | Session stored as a plain token in a CLI option |
| `EncryptedOptionTokenSession` | Token CLI session with encrypted payload        |

### Other

| Class          | Description                                |
|:---------------|:-------------------------------------------|
| `CacheSession` | Session data backed by the Cache component |
| `LogSession`   | Logs all session operations                |
| `NullSession`  | No-op session for testing                  |

The active implementation is resolved from the container as `SessionContract`.
Configure the default via your `Env` class.

## Cookie Parameters

`Valkyrja\Session\Data\CookieParams` holds the cookie configuration used by
`PhpSession` and cookie-based sessions:

| Property   | Default          | Description                              |
|:-----------|:-----------------|:-----------------------------------------|
| `path`     | `'/'`            | Cookie path                              |
| `domain`   | `''`             | Cookie domain                            |
| `lifetime` | `0`              | Cookie lifetime in seconds (0 = session) |
| `secure`   | `false`          | HTTPS only                               |
| `httpOnly` | `false`          | HTTP only (not accessible to JS)         |
| `sameSite` | `SameSite::NONE` | SameSite cookie policy                   |

## Configuration

| Env Constant                     | Default             | Description                               |
|:---------------------------------|:--------------------|:------------------------------------------|
| `SESSION_DEFAULT`                | `PhpSession::class` | Implementation bound to `SessionContract` |
| `SESSION_PHP_ID`                 | —                   | PHP session ID                            |
| `SESSION_PHP_NAME`               | —                   | PHP session name                          |
| `SESSION_COOKIE_PARAM_PATH`      | `'/'`               | Cookie path                               |
| `SESSION_COOKIE_PARAM_DOMAIN`    | —                   | Cookie domain                             |
| `SESSION_COOKIE_PARAM_LIFETIME`  | `0`                 | Cookie lifetime in seconds                |
| `SESSION_COOKIE_PARAM_SECURE`    | `false`             | HTTPS-only cookie                         |
| `SESSION_COOKIE_PARAM_HTTP_ONLY` | `false`             | HTTP-only cookie                          |
| `SESSION_COOKIE_PARAM_SAME_SITE` | `SameSite::NONE`    | SameSite policy                           |
| `SESSION_JWT_OPTION_NAME`        | `'token'`           | CLI option name for JWT sessions          |
| `SESSION_JWT_HEADER_NAME`        | `'Authorization'`   | HTTP header name for JWT sessions         |
| `SESSION_TOKEN_OPTION_NAME`      | —                   | CLI option name for token sessions        |
| `SESSION_TOKEN_HEADER_NAME`      | —                   | HTTP header name for token sessions       |

## Service Registration

The Session service provider registers `SessionContract` and all session
implementation singletons, along with the `CookieParams` data object.