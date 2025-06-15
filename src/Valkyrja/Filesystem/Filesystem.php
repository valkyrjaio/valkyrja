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

use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Filesystem\Config\Configuration;
use Valkyrja\Filesystem\Contract\Filesystem as Contract;
use Valkyrja\Filesystem\Driver\Contract\Driver;
use Valkyrja\Filesystem\Enum\Visibility;
use Valkyrja\Filesystem\Exception\RuntimeException;
use Valkyrja\Filesystem\Factory\Contract\Factory;

/**
 * Class Filesystem.
 *
 * @author Melech Mizrachi
 */
class Filesystem implements Contract
{
    /**
     * @var Driver[]
     */
    protected array $drivers = [];

    /**
     * Filesystem constructor.
     */
    public function __construct(
        protected Factory $factory = new \Valkyrja\Filesystem\Factory\Factory(),
        protected Config $config = new Config()
    ) {
    }

    /**
     * @inheritDoc
     */
    public function use(string|null $name = null): Driver
    {
        // The configuration name to use
        $name ??= $this->config->defaultConfiguration;

        return $this->drivers[$name]
            ??= $this->createDriverForName($name);
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
    public function metadata(string $path): array|null
    {
        return $this->use()->metadata($path);
    }

    /**
     * @inheritDoc
     */
    public function mimetype(string $path): string|null
    {
        return $this->use()->mimetype($path);
    }

    /**
     * @inheritDoc
     */
    public function size(string $path): int|null
    {
        return $this->use()->size($path);
    }

    /**
     * @inheritDoc
     */
    public function timestamp(string $path): int|null
    {
        return $this->use()->timestamp($path);
    }

    /**
     * @inheritDoc
     */
    public function visibility(string $path): string|null
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
    public function listContents(string|null $directory = null, bool $recursive = false): array
    {
        return $this->use()->listContents($directory, $recursive);
    }

    /**
     * Create a driver for a given name.
     */
    protected function createDriverForName(string $name): Driver
    {
        // The config to use
        $config = $this->config->configurations->$name
            ?? throw new InvalidArgumentException("$name is not a valid configuration");

        if (! $config instanceof Configuration) {
            throw new RuntimeException("$name is an invalid configuration");
        }

        // The driver to use
        $driverClass = $config->driverClass;
        // The adapter to use
        $adapterClass = $config->adapterClass;

        return $this->factory->createDriver($driverClass, $adapterClass, $config);
    }
}
