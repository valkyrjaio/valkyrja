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

namespace Valkyrja\Http\Client;

use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Exception\RuntimeException;
use Valkyrja\Http\Client\Config\Configuration;
use Valkyrja\Http\Client\Contract\Client as Contract;
use Valkyrja\Http\Client\Driver\Contract\Driver;
use Valkyrja\Http\Client\Factory\Contract\Factory;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;

/**
 * Class Client.
 *
 * @author Melech Mizrachi
 */
class Client implements Contract
{
    /**
     * @var Driver[]
     */
    protected array $drivers = [];

    /**
     * Client constructor.
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

        return $this->drivers[$name]
            ??= $this->createDriverForName($name);
    }

    /**
     * @inheritDoc
     */
    public function request(ServerRequest $request): Response
    {
        return $this->use()->request($request);
    }

    /**
     * @inheritDoc
     */
    public function get(ServerRequest $request): Response
    {
        return $this->use()->get($request);
    }

    /**
     * @inheritDoc
     */
    public function post(ServerRequest $request): Response
    {
        return $this->use()->post($request);
    }

    /**
     * @inheritDoc
     */
    public function head(ServerRequest $request): Response
    {
        return $this->use()->head($request);
    }

    /**
     * @inheritDoc
     */
    public function put(ServerRequest $request): Response
    {
        return $this->use()->put($request);
    }

    /**
     * @inheritDoc
     */
    public function patch(ServerRequest $request): Response
    {
        return $this->use()->patch($request);
    }

    /**
     * @inheritDoc
     */
    public function delete(ServerRequest $request): Response
    {
        return $this->use()->delete($request);
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
