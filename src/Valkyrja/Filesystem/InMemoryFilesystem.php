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

namespace Valkyrja\Filesystem;

use Override;
use Valkyrja\Exception\RuntimeException;
use Valkyrja\Filesystem\Contract\Filesystem as Contract;
use Valkyrja\Filesystem\Data\InMemoryFile;
use Valkyrja\Filesystem\Data\InMemoryMetadata;
use Valkyrja\Filesystem\Enum\Visibility;
use Valkyrja\Filesystem\Exception\UnableToReadContentsException;

use function fread;
use function str_starts_with;
use function time;

/**
 * Class InMemoryFilesystem.
 *
 * @author Melech Mizrachi
 */
class InMemoryFilesystem implements Contract
{
    /**
     * @var array<string, InMemoryFile>
     */
    protected array $files = [];

    public function __construct(
        InMemoryFile ...$files
    ) {
        foreach ($files as $file) {
            $this->files[$file->name] = $file;
        }
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function exists(string $path): bool
    {
        return isset($this->files[$path]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function read(string $path): string
    {
        return $this->files[$path]->contents
            ?? throw new UnableToReadContentsException("Error reading file contents for $path");
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
    #[Override]
    public function writeStream(string $path, $resource): bool
    {
        $pathContents = fread($resource, 4096);

        if ($pathContents === false) {
            throw new RuntimeException('Failed to read provided resource');
        }

        $this->files[$path] = new InMemoryFile($path, $pathContents, timestamp: time());

        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function update(string $path, string $contents): bool
    {
        return $this->write($path, $contents);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function updateStream(string $path, $resource): bool
    {
        return $this->writeStream($path, $resource);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function put(string $path, string $contents): bool
    {
        return $this->write($path, $contents);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function putStream(string $path, $resource): bool
    {
        return $this->writeStream($path, $resource);
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
    #[Override]
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
    #[Override]
    public function delete(string $path): bool
    {
        unset($this->files[$path]);

        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function metadata(string $path): array|null
    {
        return $this->getMetadataInternal($path)?->toArray();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function mimetype(string $path): string|null
    {
        return $this->getMetadataInternal($path)->mimetype ?? null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function size(string $path): int|null
    {
        return $this->getMetadataInternal($path)->size ?? null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function timestamp(string $path): int|null
    {
        return $this->files[$path]->timestamp ?? null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function visibility(string $path): string|null
    {
        return $this->getMetadataInternal($path)->visibility ?? null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
    #[Override]
    public function setVisibilityPublic(string $path): bool
    {
        return $this->setVisibility($path, Visibility::PUBLIC);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setVisibilityPrivate(string $path): bool
    {
        return $this->setVisibility($path, Visibility::PRIVATE);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function createDir(string $path): bool
    {
        $this->files[$path] = new InMemoryFile($path, timestamp: time());

        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
    #[Override]
    public function listContents(string|null $directory = null, bool $recursive = false): array
    {
        $directory ??= '';

        $contents = [];

        foreach ($this->files as $filePath => $file) {
            if (str_starts_with($filePath, $directory)) {
                $contents[] = [
                    'path'     => $filePath,
                    'contents' => $file->contents,
                ];
            }
        }

        return $contents;
    }

    protected function getMetadataInternal(string $path): InMemoryMetadata|null
    {
        return $this->files[$path]->metadata ?? null;
    }
}
