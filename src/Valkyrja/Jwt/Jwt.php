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

namespace Valkyrja\Jwt;

use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Jwt\Contract\Jwt as Contract;
use Valkyrja\Jwt\Driver\Contract\Driver;
use Valkyrja\Jwt\Factory\Contract\Factory;

/**
 * Class Jwt.
 *
 * @author Melech Mizrachi
 */
class Jwt implements Contract
{
    /**
     * JWT constructor.
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
    public function encode(array $payload): string
    {
        return $this->use()->encode($payload);
    }

    /**
     * @inheritDoc
     */
    public function decode(string $jwt): array
    {
        return $this->use()->decode($jwt);
    }
}
