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

namespace Valkyrja\Client\Driver;

use Valkyrja\Client\Adapter\Contract\Adapter;
use Valkyrja\Client\Driver\Contract\Driver as Contract;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 */
class Driver implements Contract
{
    /**
     * Driver constructor.
     */
    public function __construct(
        protected Adapter $adapter
    ) {
    }

    /**
     * @inheritDoc
     */
    public function request(ServerRequest $request): Response
    {
        return $this->adapter->request($request);
    }

    /**
     * @inheritDoc
     */
    public function get(ServerRequest $request): Response
    {
        return $this->adapter->get($request);
    }

    /**
     * @inheritDoc
     */
    public function post(ServerRequest $request): Response
    {
        return $this->adapter->post($request);
    }

    /**
     * @inheritDoc
     */
    public function head(ServerRequest $request): Response
    {
        return $this->adapter->head($request);
    }

    /**
     * @inheritDoc
     */
    public function put(ServerRequest $request): Response
    {
        return $this->adapter->put($request);
    }

    /**
     * @inheritDoc
     */
    public function patch(ServerRequest $request): Response
    {
        return $this->adapter->patch($request);
    }

    /**
     * @inheritDoc
     */
    public function delete(ServerRequest $request): Response
    {
        return $this->adapter->delete($request);
    }
}
