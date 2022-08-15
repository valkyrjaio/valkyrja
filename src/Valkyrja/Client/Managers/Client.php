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

namespace Valkyrja\Client\Managers;

use Valkyrja\Client\Client as Contract;
use Valkyrja\Client\Driver;
use Valkyrja\Client\Loader;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Support\Manager\Managers\Manager;

/**
 * Class Client.
 *
 * @author Melech Mizrachi
 *
 * @property Loader $loader
 */
class Client extends Manager implements Contract
{
    /**
     * Client constructor.
     *
     * @param Loader $loader The loader
     * @param array  $config The config
     */
    public function __construct(Loader $loader, array $config)
    {
        parent::__construct($loader, $config);

        $this->configurations = $config['clients'];
    }

    /**
     * @inheritDoc
     */
    public function use(string $name = null): Driver
    {
        return parent::use($name);
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
