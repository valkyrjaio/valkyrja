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

use Valkyrja\Filesystem\Adapter\Contract\Adapter;
use Valkyrja\Filesystem\Contract\Filesystem as Contract;
use Valkyrja\Filesystem\Driver\Contract\Driver;
use Valkyrja\Filesystem\Enum\Visibility;
use Valkyrja\Filesystem\Factory\Contract\Factory;
use Valkyrja\Manager\Manager;

/**
 * Class Filesystem.
 *
 * @author Melech Mizrachi
 *
 * @extends Manager<Adapter, Driver, Factory>
 *
 * @property Factory $factory
 */
class Filesystem extends Manager implements Contract
{
    /**
     * Filesystem constructor.
     *
     * @param Factory                     $factory The factory
     * @param Config|array<string, mixed> $config  The config
     */
    public function __construct(
        Factory $factory = new \Valkyrja\Filesystem\Factory\Factory(),
        Config|array $config = new Config\Filesystem(setup: true)
    ) {
        parent::__construct($factory, $config);

        $this->configurations = $config['disks'];
    }

    /**
     * @inheritDoc
     */
    public function use(?string $name = null): Driver
    {
        /** @var Driver $driver */
        $driver = parent::use($name);

        return $driver;
    }

    /**
     * @inheritDoc
     */
    public function exists(string $path): bool
    {
        return $this->use()->exists($path);
    }

    /**
     * @inheritDoc
     */
    public function read(string $path): string
    {
        return $this->use()->read($path);
    }

    /**
     * @inheritDoc
     */
    public function write(string $path, string $contents): bool
    {
        return $this->use()->write($path, $contents);
    }

    /**
     * @inheritDoc
     */
    public function writeStream(string $path, $resource): bool
    {
        return $this->use()->writeStream($path, $resource);
    }

    /**
     * @inheritDoc
     */
    public function update(string $path, string $contents): bool
    {
        return $this->use()->update($path, $contents);
    }

    /**
     * @inheritDoc
     */
    public function updateStream(string $path, $resource): bool
    {
        return $this->use()->updateStream($path, $resource);
    }

    /**
     * @inheritDoc
     */
    public function put(string $path, string $contents): bool
    {
        return $this->use()->put($path, $contents);
    }

    /**
     * @inheritDoc
     */
    public function putStream(string $path, $resource): bool
    {
        return $this->use()->putStream($path, $resource);
    }

    /**
     * @inheritDoc
     */
    public function rename(string $path, string $newPath): bool
    {
        return $this->use()->rename($path, $newPath);
    }

    /**
     * @inheritDoc
     */
    public function copy(string $path, string $newPath): bool
    {
        return $this->use()->copy($path, $newPath);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $path): bool
    {
        return $this->use()->delete($path);
    }

    /**
     * @inheritDoc
     */
    public function metadata(string $path): ?array
    {
        return $this->use()->metadata($path);
    }

    /**
     * @inheritDoc
     */
    public function mimetype(string $path): ?string
    {
        return $this->use()->mimetype($path);
    }

    /**
     * @inheritDoc
     */
    public function size(string $path): ?int
    {
        return $this->use()->size($path);
    }

    /**
     * @inheritDoc
     */
    public function timestamp(string $path): ?int
    {
        return $this->use()->timestamp($path);
    }

    /**
     * @inheritDoc
     */
    public function visibility(string $path): ?string
    {
        return $this->use()->visibility($path);
    }

    /**
     * @inheritDoc
     */
    public function setVisibility(string $path, Visibility $visibility): bool
    {
        return $this->use()->setVisibility($path, $visibility);
    }

    /**
     * @inheritDoc
     */
    public function setVisibilityPublic(string $path): bool
    {
        return $this->use()->setVisibilityPublic($path);
    }

    /**
     * @inheritDoc
     */
    public function setVisibilityPrivate(string $path): bool
    {
        return $this->use()->setVisibilityPrivate($path);
    }

    /**
     * @inheritDoc
     */
    public function createDir(string $path): bool
    {
        return $this->use()->createDir($path);
    }

    /**
     * @inheritDoc
     */
    public function deleteDir(string $path): bool
    {
        return $this->use()->deleteDir($path);
    }

    /**
     * @inheritDoc
     */
    public function listContents(?string $directory = null, bool $recursive = false): array
    {
        return $this->use()->listContents($directory, $recursive);
    }
}
