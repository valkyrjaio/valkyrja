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

namespace Valkyrja\Crypt;

use Valkyrja\Crypt\Contract\Crypt as Contract;
use Valkyrja\Crypt\Driver\Contract\Driver;
use Valkyrja\Crypt\Factory\Contract\Factory;
use Valkyrja\Exception\InvalidArgumentException;

/**
 * Class Crypt.
 *
 * @author Melech Mizrachi
 */
class Crypt implements Contract
{
    /**
     * Crypt constructor.
     */
    public function __construct(
        protected Factory $factory,
        protected Config $config
    ) {
    }

    /**
     * @inheritDoc
     */
    public function use(string|null $name = null): Driver
    {
        // The configuration name to use
        $name ??= $this->config->defaultConfiguration;
        // The config to use
        $config = $this->config->configurations->$name
            ?? throw new InvalidArgumentException("$name is not a valid configuration");
        // The driver to use
        $driverClass = $config->driverClass;
        // The adapter to use
        $adapterClass = $config->adapterClass;
        // The cache key to use
        $cacheKey = $name . $adapterClass;

        return $this->drivers[$cacheKey]
            ?? $this->factory->createDriver($driverClass, $adapterClass, $config);
    }

    /**
     * @inheritDoc
     */
    public function isValidEncryptedMessage(string $encrypted): bool
    {
        return $this->use()->isValidEncryptedMessage($encrypted);
    }

    /**
     * @inheritDoc
     */
    public function encrypt(string $message, string|null $key = null): string
    {
        return $this->use()->encrypt($message, $key);
    }

    /**
     * @inheritDoc
     */
    public function decrypt(string $encrypted, string|null $key = null): string
    {
        return $this->use()->decrypt($encrypted, $key);
    }

    /**
     * @inheritDoc
     */
    public function encryptArray(array $array, string|null $key = null): string
    {
        return $this->use()->encryptArray($array, $key);
    }

    /**
     * @inheritDoc
     */
    public function decryptArray(string $encrypted, string|null $key = null): array
    {
        return $this->use()->decryptArray($encrypted, $key);
    }

    /**
     * @inheritDoc
     */
    public function encryptObject(object $object, string|null $key = null): string
    {
        return $this->use()->encryptObject($object, $key);
    }

    /**
     * @inheritDoc
     */
    public function decryptObject(string $encrypted, string|null $key = null): object
    {
        return $this->use()->decryptObject($encrypted, $key);
    }
}
