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

namespace Valkyrja\Client\Drivers;

use Valkyrja\Client\Adapter;
use Valkyrja\Client\Driver as Contract;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Manager\Drivers\Driver as ParentDriver;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 *
 * @property Adapter $adapter
 */
class Driver extends ParentDriver implements Contract
{
    /**
     * Driver constructor.
     *
     * @param Adapter $adapter The adapter
     */
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
    }

    /**
     * @inheritDoc
     */
    public function request(Request $request): Response
    {
        return $this->adapter->request($request);
    }

    /**
     * @inheritDoc
     */
    public function get(Request $request): Response
    {
        return $this->adapter->get($request);
    }

    /**
     * @inheritDoc
     */
    public function post(Request $request): Response
    {
        return $this->adapter->post($request);
    }

    /**
     * @inheritDoc
     */
    public function head(Request $request): Response
    {
        return $this->adapter->head($request);
    }

    /**
     * @inheritDoc
     */
    public function put(Request $request): Response
    {
        return $this->adapter->put($request);
    }

    /**
     * @inheritDoc
     */
    public function patch(Request $request): Response
    {
        return $this->adapter->patch($request);
    }

    /**
     * @inheritDoc
     */
    public function delete(Request $request): Response
    {
        return $this->adapter->delete($request);
    }
}
