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
 * @implements UploadedFileCollectionContract<UploadedFileContract|self>
 */
class UploadedFileCollection implements UploadedFileCollectionContract
{
    /** @var array<array-key, UploadedFileContract|self> */
    protected array $files = [];

    /**
     * The position during iteration.
     *
     * @var int
     */
    protected int $position = 0;

    /**
     * @param UploadedFileContract|self ...$files The files
     */
    public function __construct(UploadedFileContract|self ...$files)
    {
        $this->files = $files;
    }

    /**
     * Create a new instance from an array.
     *
     * @param array<array-key, mixed> $data The data to create from
     */
    public function fromArray(array $data): static
    {
        $params = [];

        /**
         * @var array-key $name
         * @var mixed     $param
         */
        foreach ($data as $name => $param) {
            if (is_array($param)) {
                $param = static::fromArray($param);
            }

            $this->validateFile($param);

            $params[$name] = $param;
        }

        return new static(...$params);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasFile(int|string $name): bool
    {
        return isset($this->files[$name]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getFile(int|string $name): UploadedFileContract|self|null
    {
        return $this->files[$name]
            ?? null;
    }

    /**
     * @inheritDoc
     *
     * @psalm-suppress InvalidReturnStatement
     */
    #[Override]
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @inheritDoc
     *
     * @psalm-suppress InvalidReturnStatement
     */
    #[Override]
    public function onlyFiles(string|int ...$names): array
    {
        return array_filter(
            $this->files,
            static fn (string|int $name): bool => in_array($name, $names, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * @inheritDoc
     *
     * @psalm-suppress InvalidReturnStatement
     */
    #[Override]
    public function exceptFiles(string|int ...$names): array
    {
        return array_filter(
            $this->files,
            static fn (string|int $name): bool => ! in_array($name, $names, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withFiles(array $files): static
    {
        $this->validateFiles($files);

        $new = clone $this;

        $new->files = $files;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedFiles(UploadedFileCollectionContract|UploadedFileContract ...$files): static
    {
        $this->validateFiles($files);

        $new = clone $this;

        $new->files = array_merge($new->files, $files);

        return $new;
    }

    /**
     * Validate files.
     *
     * @param array<array-key, mixed> $params The params to validate
     *
     * @psalm-assert array<array-key, UploadedFileContract|self> $params
     *
     * @phpstan-assert array<array-key, UploadedFileContract|self> $params
     */
    protected function validateFiles(array $params): void
    {
        foreach ($params as $param) {
            $this->validateFile($param);
        }
    }

    /**
     * Validate a file.
     *
     * @psalm-assert UploadedFileContract|self $param
     *
     * @phpstan-assert UploadedFileContract|self $param
     */
    protected function validateFile(mixed $param): void
    {
        if (! $param instanceof static && ! $param instanceof UploadedFileContract) {
            throw new InvalidArgumentException('Param must be an UploadedFileContract or UploadedFileData instance');
        }
    }
}
