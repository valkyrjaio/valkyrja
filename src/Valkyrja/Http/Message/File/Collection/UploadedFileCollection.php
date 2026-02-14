<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Message\File\Collection;

use Override;
use Valkyrja\Http\Message\File\Collection\Contract\UploadedFileCollectionContract;
use Valkyrja\Http\Message\File\Contract\UploadedFileContract;
use Valkyrja\Http\Message\File\Throwable\Exception\InvalidArgumentException;

use function in_array;
use function is_array;

use const ARRAY_FILTER_USE_KEY;

/**
 * @implements UploadedFileCollectionContract<UploadedFileContract|UploadedFileCollectionContract>
 */
class UploadedFileCollection implements UploadedFileCollectionContract
{
    /** @var array<array-key, UploadedFileContract|UploadedFileCollectionContract> */
    protected array $files = [];

    /**
     * The position during iteration.
     *
     * @var int
     */
    protected int $position = 0;

    /**
     * @param array<array-key, UploadedFileContract|UploadedFileCollectionContract> $files The files
     */
    public function __construct(array $files = [])
    {
        $this->validateFiles($files);

        $this->files = $files;
    }

    /**
     * Create a new instance from an array.
     *
     * @param array<array-key, mixed> $data The data to create from
     */
    public static function fromArray(array $data): static
    {
        $params = [];

        /**
         * @var array-key                                           $name
         * @var scalar|object|array<array-key, mixed>|resource|null $param
         */
        foreach ($data as $name => $param) {
            if (is_array($param)) {
                $param = static::fromArray($param);
            }

            static::validateFile($param);

            $params[$name] = $param;
        }

        return new static($params);
    }

    /**
     * Validate a file.
     *
     * @psalm-assert UploadedFileContract|self $param
     *
     * @phpstan-assert UploadedFileContract|self $param
     */
    protected static function validateFile(mixed $param): void
    {
        if (! $param instanceof UploadedFileCollectionContract && ! $param instanceof UploadedFileContract) {
            throw new InvalidArgumentException('Param must be an UploadedFileContract or UploadedFileData instance');
        }
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function has(int|string $key): bool
    {
        return isset($this->files[$key]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function get(int|string $key): UploadedFileContract|UploadedFileCollectionContract|null
    {
        return $this->files[$key]
            ?? null;
    }

    /**
     * @inheritDoc
     *
     * @psalm-suppress InvalidReturnStatement
     */
    #[Override]
    public function getAll(): array
    {
        return $this->files;
    }

    /**
     * @inheritDoc
     *
     * @psalm-suppress InvalidReturnStatement
     */
    #[Override]
    public function getOnly(string|int ...$keys): array
    {
        return array_filter(
            $this->files,
            static fn (string|int $name): bool => in_array($name, $keys, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * @inheritDoc
     *
     * @psalm-suppress InvalidReturnStatement
     */
    #[Override]
    public function getAllExcept(string|int ...$keys): array
    {
        return array_filter(
            $this->files,
            static fn (string|int $name): bool => ! in_array($name, $keys, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function with(array $collection): static
    {
        $this->validateFiles($collection);

        $new = clone $this;

        $new->files = $collection;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAdded(array $collection): static
    {
        $this->validateFiles($collection);

        $new = clone $this;

        $new->files = array_merge($new->files, $collection);

        return $new;
    }

    /**
     * Validate files.
     *
     * @param array<array-key, mixed> $params The params to validate
     *
     * @psalm-assert array<array-key, UploadedFileContract|UploadedFileCollectionContract> $params
     *
     * @phpstan-assert array<array-key, UploadedFileContract|UploadedFileCollectionContract> $params
     */
    protected function validateFiles(array $params): void
    {
        /**
         * @var scalar|object|array<array-key, mixed>|resource|null $param
         */
        foreach ($params as $param) {
            static::validateFile($param);
        }
    }
}
