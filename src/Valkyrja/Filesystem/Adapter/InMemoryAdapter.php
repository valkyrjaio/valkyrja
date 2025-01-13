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

namespace Valkyrja\Filesystem\Adapter;

use Valkyrja\Filesystem\Adapter\Contract\Adapter as Contract;
use Valkyrja\Filesystem\Data\InMemoryFile;
use Valkyrja\Filesystem\Data\InMemoryMetadata;
use Valkyrja\Filesystem\Enum\Visibility;
use Valkyrja\Filesystem\Exception\UnableToReadContentsException;

use function fread;
use function str_starts_with;
use function time;

/**
 * Class InMemoryAdapter.
 *
 * @author Melech Mizrachi
 */
class InMemoryAdapter implements Contract
{
    /**
     * @var InMemoryFile[]
     */
    protected array $files = [];

    public function __construct(
        InMemoryFile ...$files
    ) {
        $this->files = $files;
    }

    /**
     * @inheritDoc
     */
    public function exists(string $path): bool
    {
        return isset($this->files[$path]);
    }

    /**
     * @inheritDoc
     */
    public function read(string $path): string
    {
        return $this->files[$path]->contents
            ?? throw new UnableToReadContentsException("Error reading file contents for $path");
    }

    /**
     * @inheritDoc
     */
    public function write(string $path, string $contents): bool
    {
        $this->files[$path] = new InMemoryFile($path, $contents, timestamp: time());

        return true;
    }

    /**
     * @inheritDoc
     *
     * @param resource $resource The resource
     */
    public function writeStream(string $path, $resource): bool
    {
        $this->files[$path] = new InMemoryFile($path, fread($resource, 4096), timestamp: time());

        return true;
    }

    /**
     * @inheritDoc
     */
    public function update(string $path, string $contents): bool
    {
        return $this->write($path, $contents);
    }

    /**
     * @inheritDoc
     */
    public function updateStream(string $path, $resource): bool
    {
        return $this->writeStream($path, $resource);
    }

    /**
     * @inheritDoc
     */
    public function put(string $path, string $contents): bool
    {
        return $this->write($path, $contents);
    }

    /**
     * @inheritDoc
     */
    public function putStream(string $path, $resource): bool
    {
        return $this->writeStream($path, $resource);
    }

    /**
     * @inheritDoc
     */
    public function rename(string $path, string $newPath): bool
    {
        if ($this->exists($newPath) || ! $this->exists($path)) {
            return false;
        }

        $this->files[$newPath] = $this->files[$path];

        $this->delete($path);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function copy(string $path, string $newPath): bool
    {
        if ($this->exists($newPath) || ! $this->exists($path)) {
            return false;
        }

        $this->files[$newPath] = $this->files[$path];

        return true;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $path): bool
    {
        unset($this->files[$path]);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function metadata(string $path): array|null
    {
        return $this->getMetadataInternal($path)?->toArray() ?? null;
    }

    /**
     * @inheritDoc
     */
    public function mimetype(string $path): string|null
    {
        return $this->getMetadataInternal($path)->mimetype ?? null;
    }

    /**
     * @inheritDoc
     */
    public function size(string $path): int|null
    {
        return $this->getMetadataInternal($path)->size ?? null;
    }

    /**
     * @inheritDoc
     */
    public function timestamp(string $path): int|null
    {
        return $this->files[$path]->timestamp ?? null;
    }

    /**
     * @inheritDoc
     */
    public function visibility(string $path): string|null
    {
        return $this->getMetadataInternal($path)->visibility ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setVisibility(string $path, Visibility $visibility): bool
    {
        if (! $this->exists($path)) {
            return false;
        }

        $this->files[$path]->metadata->visibility = $visibility->value;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function setVisibilityPublic(string $path): bool
    {
        return $this->setVisibility($path, Visibility::PUBLIC);
    }

    /**
     * @inheritDoc
     */
    public function setVisibilityPrivate(string $path): bool
    {
        return $this->setVisibility($path, Visibility::PRIVATE);
    }

    /**
     * @inheritDoc
     */
    public function createDir(string $path): bool
    {
        $this->files[$path] = new InMemoryFile($path, timestamp: time());

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteDir(string $path): bool
    {
        foreach ($this->files as $filePath => $file) {
            if (str_starts_with($filePath, $path)) {
                unset($this->files[$filePath]);
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function listContents(?string $directory = null, bool $recursive = false): array
    {
        $directory ??= '';

        $contents = [];

        foreach ($this->files as $filePath => $file) {
            if (str_starts_with($filePath, $directory)) {
                $contents[$filePath] = $file->contents;
            }
        }

        return $contents;
    }

    protected function getMetadataInternal(string $path): InMemoryMetadata|null
    {
        return $this->files[$path]->metadata ?? null;
    }
}
