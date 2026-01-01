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

use Valkyrja\Http\Message\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Routing\Attribute\Parameter;
use Valkyrja\Http\Routing\Attribute\Route;
use Valkyrja\Http\Routing\Attribute\Route\Middleware;
use Valkyrja\Http\Routing\Attribute\Route\RequestMethod\Get;
use Valkyrja\Http\Routing\Attribute\Route\RequestMethod\Head;
use Valkyrja\Http\Routing\Attribute\Route\RequestMethod\Post;
use Valkyrja\Http\Routing\Attribute\Route\RequestStruct;
use Valkyrja\Http\Routing\Attribute\Route\ResponseStruct;
use Valkyrja\Http\Routing\Constant\Regex;
use Valkyrja\Tests\Classes\Http\Middleware\RouteDispatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteMatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\SendingResponseMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\TerminatedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\ThrowableCaughtMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Struct\IndexedJsonRequestStructEnum;
use Valkyrja\Tests\Classes\Http\Struct\ResponseStructEnum;

/**
 * Controller class to test routes.
 *
 * @author Melech Mizrachi
 */
class ControllerClass
{
    /** @var non-empty-string */
    public const string WELCOME_PATH = '/';
    /** @var non-empty-string */
    public const string WELCOME_NAME = 'welcome';
    /** @var non-empty-string */
    public const string PARAMETERS_PATH = '/parameters/{name}';
    /** @var non-empty-string */
    public const string PARAMETERS_NAME = 'parameters';
    /** @var non-empty-string */
    public const string PARAMETERS_PARAMETER_NAME = 'name';

    #[Route(path: self::WELCOME_PATH, name: self::WELCOME_NAME)]
    public function welcome(): ResponseContract
    {
        return Response::create('welcome');
    }

    #[Get]
    #[Head]
    #[Post]
    #[Route(path: self::PARAMETERS_PATH, name: self::PARAMETERS_NAME)]
    #[Middleware(RouteDispatchedMiddlewareClass::class)]
    #[Middleware(RouteMatchedMiddlewareClass::class)]
    #[Middleware(SendingResponseMiddlewareClass::class)]
    #[Middleware(TerminatedMiddlewareClass::class)]
    #[Middleware(ThrowableCaughtMiddlewareClass::class)]
    #[RequestStruct(IndexedJsonRequestStructEnum::class)]
    #[ResponseStruct(ResponseStructEnum::class)]
    public function parameters(
        ResponseFactoryContract $responseFactory,
        #[Parameter(name: self::PARAMETERS_PARAMETER_NAME, regex: Regex::ALPHA)]
        string $name,
    ): ResponseContract {
        return $responseFactory->createResponse(
            content: "parameters$name"
        );
    }
}
