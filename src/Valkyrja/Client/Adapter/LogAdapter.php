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

use JsonException;
use Valkyrja\Client\Adapter\Contract\LogAdapter as Contract;
use Valkyrja\Client\Config\LogConfiguration;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Log\Driver\Contract\Driver as Logger;
use Valkyrja\Type\BuiltIn\Support\Obj;

/**
 * Class LogAdapter.
 *
 * @author Melech Mizrachi
 */
class LogAdapter implements Contract
{
    /**
     * LogAdapter constructor.
     */
    public function __construct(
        protected Logger $logger,
        protected ResponseFactory $responseFactory,
        protected LogConfiguration $config
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function request(ServerRequest $request): Response
    {
        $optionsString = Obj::toString($request);

        $this->logger->info(
            static::class . " request: {$request->getMethod()->value}, uri {$request->getUri()->__toString()}, options $optionsString"
        );

        return $this->responseFactory->createResponse();
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function get(ServerRequest $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::GET));
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function post(ServerRequest $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::POST));
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function head(ServerRequest $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::HEAD));
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function put(ServerRequest $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::PUT));
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function patch(ServerRequest $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::PATCH));
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function delete(ServerRequest $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::DELETE));
    }
}
