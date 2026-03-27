# Documentation

## Prologue

- [Getting Started](GETTING_STARTED.md) — Installation, configuration, and first steps
- [Request Lifecycle](LIFECYCLE.md) — What happens from entry point to response, for both HTTP and CLI
- [Versioning & Release Process](VERSIONING_AND_RELEASE_PROCESS.md) — Release schedule, support policy, and development branches

## Core Concepts

- [The Application](Application/README.md) — Bootstrap sequence, typed configuration, component loading, and the data cache
- [The Container](Container/README.md) — Dependency injection, service types, service providers, and component providers
- [Event Dispatching](Event/README.md) — PSR-14 event dispatcher, listeners, and attribute registration

## HTTP

- [HTTP Routing & Middleware](Http/README.md) — Route providers, attribute-based registration, the seven-stage middleware pipeline, request and response handling, and response caching

## CLI

- [CLI Routing & Commands](Cli/README.md) — Command providers, attribute-based registration, arguments and options, the six-stage middleware pipeline, and built-in commands
- [Bin](Bin/README.md) — Console binary entry point

## Data & Types

- [Type System](Type/README.md) — TypeContract, primitive wrappers, strings, identifiers, UUID/VLID/ULID, models, collections, and JSON
- [Models](Type/Model/README.md) — Property access callables, casting, exposure, original properties, and indexed models
- [Validation](Validation/README.md) — Rule contracts, built-in rules (Is, String, Int, ORM), and the validator

## Database

- [ORM](Orm/README.md) — PDO-backed data access with repositories, fluent query builder, transactions, schema management, and migrations

## Authentication

- [Auth](Auth/README.md) — Authenticators, user entities, stores, password hashing, retrieval strategies, and session-backed authentication
- [Session](Session/README.md) — HTTP and CLI session backends, CSRF tokens, cookie parameters, and JWT/token sessions
- [Crypt](Crypt/README.md) — Symmetric authenticated encryption via libsodium secretbox
- [JWT](Jwt/README.md) — JSON Web Token encoding and decoding with pluggable algorithm support

## Services

- [Cache](Cache/README.md) — Cache contract, tagging, Redis/Log/Null backends
- [Broadcast](Broadcast/README.md) — Real-time event broadcasting via Pusher, with encrypted and null variants
- [Filesystem](Filesystem/README.md) — Local, S3, and in-memory filesystems with a unified API and visibility control
- [Log](Log/README.md) — PSR-3 compliant logging via Monolog, with Log and Null implementations
- [Mail](Mail/README.md) — Email via Mailgun or PHPMailer SMTP, with fluent immutable message building
- [SMS](Sms/README.md) — Text messaging via Vonage, with Log and Null implementations

## Views & Templates

- [View](View/README.md) — PHP, Orka, and Twig renderers; full Orka template syntax reference

## Internals

- [Attribute](Attribute/README.md) — PHP 8 attribute collection across classes, methods, properties, parameters, functions, and closures
- [Reflection](Reflection/README.md) — Cached reflection wrapper for classes, methods, properties, functions, and dependency extraction
- [Support](Support/README.md) — Time/Microtime freeze utilities and file generator helpers
- [Throwable](Throwable/README.md) — Exception hierarchy, throwable handler contract, and Whoops integration
