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

namespace Valkyrja\Client;

use Valkyrja\Client\Contract\Client as Contract;
use Valkyrja\Client\Driver\Contract\Driver;
use Valkyrja\Client\Factory\Contract\Factory;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Manager\Manager;

/**
 * Class Client.
 *
 * @author Melech Mizrachi
 *
 * @extends Manager<Driver, Factory>
 *
 * @property Factory $factory
 */
class Client extends Manager implements Contract
{
    /**
     * Client constructor.
     *
     * @param Factory      $factory The factory
     * @param Config|array $config  The config
     */
    public function __construct(Factory $factory, Config|array $config)
    {
        parent::__construct($factory, $config);

        $this->configurations = $config['clients'];
    }

    /**
     * @inheritDoc
     */
    public function use(string|null $name = null): Driver
    {
        /** @var Driver $driver */
        $driver = parent::use($name);

        return $driver;
    }

    /**
     * @inheritDoc
     */
    public function request(Request $request): Response
    {
        return $this->use()->request($request);
    }

    /**
     * @inheritDoc
     */
    public function get(Request $request): Response
    {
        return $this->use()->get($request);
    }

    /**
     * @inheritDoc
     */
    public function post(Request $request): Response
    {
        return $this->use()->post($request);
    }

    /**
     * @inheritDoc
     */
    public function head(Request $request): Response
    {
        return $this->use()->head($request);
    }

    /**
     * @inheritDoc
     */
    public function put(Request $request): Response
    {
        return $this->use()->put($request);
    }

    /**
     * @inheritDoc
     */
    public function patch(Request $request): Response
    {
        return $this->use()->patch($request);
    }

    /**
     * @inheritDoc
     */
    public function delete(Request $request): Response
    {
        return $this->use()->delete($request);
    }
}
