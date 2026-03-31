# Support

## Introduction

The Support component provides small, focused utilities used across the
framework and in application code: time helpers for deterministic testing and a
file generator contract for code generation tools.

## Time

`Valkyrja\Support\Time\Time` is a static time provider that supports freezing
for tests.

```php
Time::freeze(int $time): void      // Freeze time at the given Unix timestamp
Time::unfreeze(): void             // Resume real time
Time::get(): int                   // Return the frozen time, or time() if not frozen
```

`Valkyrja\Support\Time\Microtime` mirrors the same API at microsecond precision:

```php
Microtime::freeze(float $microtime): void
Microtime::unfreeze(): void
Microtime::get(): float            // Returns frozen microtime, or microtime(true) if not frozen
```

Both classes are designed to be extended. Override `time()` or `microtime()` in
a subclass to substitute a custom time source.

The primary use case is deterministic testing. Code that calls `Time::get()`
instead of `time()` directly can be tested with a fixed timestamp:

```php
Time::freeze(1_700_000_000);

// ... exercise code that reads Time::get() ...

Time::unfreeze();
```

## File Generation

`Valkyrja\Support\Generator\Contract\FileGeneratorContract` defines the
interface for classes that write generated PHP files to disk — used by the
framework's data cache generation commands and any custom code generators you
build.

```php
public function generateFile(): GenerateStatus;
public function generateFileContents(): string;
```

`generateFileContents()` returns the string content of the file to be written.
`generateFile()` writes it to disk and returns a `GenerateStatus` enum case
indicating the outcome:

| Case      | Meaning                                     |
|:----------|:--------------------------------------------|
| `SUCCESS` | File was written successfully               |
| `FAILURE` | Write failed                                |
| `SKIPPED` | File already exists and was not overwritten |

Extend `Valkyrja\Support\Generator\Abstract\FileGenerator` to implement your own
generator. Override `generateFileContents()` to produce your file's content; the
base class handles the write operation and returns the appropriate status.