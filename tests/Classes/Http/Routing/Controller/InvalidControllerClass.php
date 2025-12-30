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

namespace Valkyrja\Tests\Classes\Http\Routing\Controller;

use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Routing\Attribute\Route;
use Valkyrja\Http\Routing\Attribute\Route\Middleware;
use Valkyrja\Tests\Classes\Http\Middleware\RequestReceivedMiddlewareClass;

/**
 * Controller class to test invalid routes.
 *
 * @author Melech Mizrachi
 */
class InvalidControllerClass
{
    /** @var non-empty-string */
    public const string INVALID_MIDDLEWARE_PATH = '/invalid-middleware';
    /** @var non-empty-string */
    public const string INVALID_MIDDLEWARE_NAME = 'invalid-middleware';

    #[Route(path: self::INVALID_MIDDLEWARE_PATH, name: self::INVALID_MIDDLEWARE_NAME)]
    // Testing an invalid middleware
    #[Middleware(RequestReceivedMiddlewareClass::class)]
    public function invalidMiddlewareTest(
        ResponseFactory $responseFactory
    ): Response {
        return $responseFactory->createResponse(
            content: 'invalidMiddlewareTest'
        );
    }
}
