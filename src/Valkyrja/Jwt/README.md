# JWT

## Introduction

The JWT component encodes and decodes JSON Web Tokens. It wraps
the [firebase/php-jwt](https://github.com/firebase/php-jwt) library and exposes
a minimal two-method contract, keeping algorithm and key configuration in your
`Env` class. A null implementation is included for testing.

## The JWT Contract

`Valkyrja\Jwt\Contract\JwtContract` defines the full API:

```php
public function encode(array $payload): string;
public function decode(string $jwt): array;
```

`encode()` takes a plain PHP array and returns a signed JWT string. `decode()`
takes a JWT string, verifies the signature, and returns the decoded payload
array.

## Supported Algorithms

`Valkyrja\Jwt\Enum\Algorithm` covers all algorithms supported by
firebase/php-jwt:

| Family | Cases                               |
|:-------|:------------------------------------|
| HMAC   | `HS256`, `HS384`, `HS512`           |
| RSA    | `RS256`, `RS384`, `RS512`           |
| PSA    | `PS256`, `PS384`, `PS512`           |
| ECDSA  | `ES256`, `ES256K`, `ES384`, `ES512` |
| EdDSA  | `EdDSA`                             |

## Implementations

| Class         | Description                                             |
|:--------------|:--------------------------------------------------------|
| `FirebaseJwt` | Delegates to firebase/php-jwt                           |
| `NullJwt`     | No-op; `encode()` returns `''`, `decode()` returns `[]` |

The active implementation is resolved from the container as `JwtContract`.
Configure the default via your `Env` class.

## Configuration

| Env Constant            | Default              | Description                                   |
|:------------------------|:---------------------|:----------------------------------------------|
| `JWT_DEFAULT`           | `FirebaseJwt::class` | Implementation bound to `JwtContract`         |
| `JWT_ALGORITHM`         | `HS256`              | Algorithm to use for signing and verification |
| `JWT_HS_KEY`            | `'key'`              | Secret key for HMAC algorithms                |
| `JWT_RS_PRIVATE_KEY`    | `'private-key'`      | Private key for RSA algorithms                |
| `JWT_RS_PUBLIC_KEY`     | `'public-key'`       | Public key for RSA algorithms                 |
| `JWT_EDDSA_PRIVATE_KEY` | `'private-key'`      | Private key for EdDSA                         |
| `JWT_EDDSA_PUBLIC_KEY`  | `'public-key'`       | Public key for EdDSA                          |

## Service Registration

The JWT service provider registers the following singletons:

| Contract / Class | Description                                 |
|:-----------------|:--------------------------------------------|
| `JwtContract`    | Active JWT manager (default: `FirebaseJwt`) |
| `FirebaseJwt`    | Firebase JWT implementation                 |
| `NullJwt`        | No-op implementation                        |