# Filesystem

## Introduction

The Filesystem component provides a uniform interface for reading, writing, and
managing files across local disk, AWS S3, and in-memory storage. All backends
are built on [Flysystem](https://flysystem.thephpleague.com/). A null
implementation is included for testing.

## The Filesystem Contract

`Valkyrja\Filesystem\Contract\FilesystemContract` is the complete file operation
interface:

```php
// Existence and reading
public function exists(string $path): bool;
public function read(string $path): string;

// Writing
public function write(string $path, string $contents): bool;
public function writeStream(string $path, $resource): bool;

// Updating
public function update(string $path, string $contents): bool;
public function updateStream(string $path, $resource): bool;

// Write or update
public function put(string $path, string $contents): bool;
public function putStream(string $path, $resource): bool;

// File management
public function rename(string $path, string $newPath): bool;
public function copy(string $path, string $newPath): bool;
public function delete(string $path): bool;

// Metadata
public function metadata(string $path): array;
public function mimetype(string $path): string;
public function size(string $path): int;
public function timestamp(string $path): int;

// Visibility
public function visibility(string $path): Visibility;
public function setVisibility(string $path, Visibility $visibility): bool;
public function setVisibilityPublic(string $path): bool;
public function setVisibilityPrivate(string $path): bool;

// Directories
public function createDir(string $path): bool;
public function deleteDir(string $path): bool;
public function listContents(string $directory = '', bool $recursive = false): array;
```

## Visibility

File visibility is represented by the `Valkyrja\Filesystem\Enum\Visibility`
enum:

| Case      | Value       |
|:----------|:------------|
| `PUBLIC`  | `'public'`  |
| `PRIVATE` | `'private'` |

## Implementations

| Class                      | Description                                             |
|:---------------------------|:--------------------------------------------------------|
| `LocalFlysystemFilesystem` | Local filesystem via Flysystem `LocalFilesystemAdapter` |
| `S3FlysystemFilesystem`    | AWS S3 via Flysystem `AwsS3V3Adapter`                   |
| `InMemoryFilesystem`       | In-memory storage; useful for testing                   |
| `NullFilesystem`           | No-op; all operations succeed silently                  |

The active implementation is resolved from the container as
`FilesystemContract`. Configure the defaults via your `Env` class.

## Configuration

### General

| Env Constant                   | Default                           | Description                                        |
|:-------------------------------|:----------------------------------|:---------------------------------------------------|
| `FILESYSTEM_DEFAULT`           | `FlysystemFilesystem::class`      | Implementation bound to `FilesystemContract`       |
| `FLYSYSTEM_FILESYSTEM_DEFAULT` | `LocalFlysystemFilesystem::class` | Flysystem backend when using `FlysystemFilesystem` |

### Local Filesystem

| Env Constant                      | Default          | Description               |
|:----------------------------------|:-----------------|:--------------------------|
| `FILESYSTEM_FLYSYSTEM_LOCAL_PATH` | `'/storage/app'` | Root path for local files |

### S3

| Env Constant                      | Default       | Description           |
|:----------------------------------|:--------------|:----------------------|
| `FILESYSTEM_FLYSYSTEM_S3_KEY`     | `'s3-key'`    | AWS access key        |
| `FILESYSTEM_FLYSYSTEM_S3_SECRET`  | `'s3-secret'` | AWS secret key        |
| `FILESYSTEM_FLYSYSTEM_S3_REGION`  | `'us-east-1'` | AWS region            |
| `FILESYSTEM_FLYSYSTEM_S3_VERSION` | `'latest'`    | AWS API version       |
| `FILESYSTEM_FLYSYSTEM_S3_BUCKET`  | `'s3-bucket'` | S3 bucket name        |
| `FILESYSTEM_FLYSYSTEM_S3_PREFIX`  | `''`          | S3 key prefix         |
| `FILESYSTEM_FLYSYSTEM_S3_OPTIONS` | `[]`          | Additional S3 options |

## Service Registration

The Filesystem service provider registers the following singletons:

| Contract / Class           | Description                                             |
|:---------------------------|:--------------------------------------------------------|
| `FilesystemContract`       | Active filesystem (default: `LocalFlysystemFilesystem`) |
| `LocalFlysystemFilesystem` | Local adapter implementation                            |
| `S3FlysystemFilesystem`    | S3 adapter implementation                               |
| `InMemoryFilesystem`       | In-memory implementation                                |
| `NullFilesystem`           | No-op implementation                                    |
| `LocalFilesystemAdapter`   | Flysystem local adapter instance                        |
| `AwsS3V3Adapter`           | Flysystem S3 adapter instance                           |