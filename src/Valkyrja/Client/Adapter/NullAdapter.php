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

namespace Valkyrja\Client\Adapter;

use Valkyrja\Client\Adapter\Contract\Adapter as Contract;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;

/**
 * Class NullAdapter.
 *
 * @author Melech Mizrachi
 */
class NullAdapter implements Contract
{
    /**
     * The response factory.
     *
     * @var ResponseFactory
     */
    protected ResponseFactory $responseFactory;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * NullAdapter constructor.
     *
     * @param ResponseFactory $responseFactory The response factory
     * @param array           $config          The config
     */
    public function __construct(ResponseFactory $responseFactory, array $config)
    {
        $this->responseFactory = $responseFactory;
        $this->config          = $config;
    }

    /**
     * @inheritDoc
     */
    public function request(ServerRequest $request): Response
    {
        return $this->responseFactory->createResponse();
    }

    /**
     * @inheritDoc
     */
    public function get(ServerRequest $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::GET));
    }

    /**
     * @inheritDoc
     */
    public function post(ServerRequest $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::GET));
    }

    /**
     * @inheritDoc
     */
    public function head(ServerRequest $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::GET));
    }

    /**
     * @inheritDoc
     */
    public function put(ServerRequest $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::GET));
    }

    /**
     * @inheritDoc
     */
    public function patch(ServerRequest $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::GET));
    }

    /**
     * @inheritDoc
     */
    public function delete(ServerRequest $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::GET));
    }
}
