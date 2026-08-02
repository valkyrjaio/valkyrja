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
`FilesystemContract`. Configure the defaults through `FilesystemConfigContract`.

## Configuration

The component reads four config contracts. Your application config class
implements only the contracts for the adapters that it uses. Each adapter
contract prefixes its properties with the adapter name, so one class can
implement several of them at once.

### `FilesystemConfigContract`

| Property            | Default                      | Description                                  |
|:--------------------|:-----------------------------|:---------------------------------------------|
| `defaultFilesystem` | `FlysystemFilesystem::class` | Implementation bound to `FilesystemContract` |

### `FilesystemFlysystemConfigContract`

| Property                     | Default                           | Description                                        |
|:-----------------------------|:----------------------------------|:---------------------------------------------------|
| `defaultFlysystemFilesystem` | `LocalFlysystemFilesystem::class` | Flysystem backend when using `FlysystemFilesystem` |

### `FilesystemFlysystemLocalConfigContract`

| Property             | Default          | Description               |
|:---------------------|:-----------------|:--------------------------|
| `flysystemLocalPath` | `'/storage/app'` | Root path for local files |

### `FilesystemFlysystemS3ConfigContract`

| Property             | Default       | Description           |
|:---------------------|:--------------|:----------------------|
| `flysystemS3Key`     | `'s3-key'`    | AWS access key        |
| `flysystemS3Secret`  | `'s3-secret'` | AWS secret key        |
| `flysystemS3Region`  | `'us-east-1'` | AWS region            |
| `flysystemS3Version` | `'latest'`    | AWS API version       |
| `flysystemS3Bucket`  | `'s3-bucket'` | S3 bucket name        |
| `flysystemS3Prefix`  | `''`          | S3 key prefix         |
| `flysystemS3Options` | `[]`          | Additional S3 options |

## Service Registration

The Filesystem service provider registers the following singletons:

| Contract / Class                        | Description                                             |
|:----------------------------------------|:--------------------------------------------------------|
| `FilesystemConfigContract`               | Component config                                        |
| `FilesystemFlysystemConfigContract`      | Flysystem backend config                                |
| `FilesystemFlysystemLocalConfigContract` | Local adapter config                                    |
| `FilesystemFlysystemS3ConfigContract`    | S3 adapter config                                       |
| `FilesystemContract`                     | Active filesystem (default: `LocalFlysystemFilesystem`) |
| `LocalFlysystemFilesystem` | Local adapter implementation                            |
| `S3FlysystemFilesystem`    | S3 adapter implementation                               |
| `InMemoryFilesystem`       | In-memory implementation                                |
| `NullFilesystem`           | No-op implementation                                    |
| `LocalFilesystemAdapter`   | Flysystem local adapter instance                        |
| `AwsS3V3Adapter`           | Flysystem S3 adapter instance                           |
