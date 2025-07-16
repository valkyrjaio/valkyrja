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
use Valkyrja\Http\Routing\Attribute\Parameter;
use Valkyrja\Http\Routing\Attribute\Route;
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
    public function welcome(): Response
    {
        return \Valkyrja\Http\Message\Response\Response::create('welcome');
    }

    #[Route\RequestMethod\Get]
    #[Route\RequestMethod\Head]
    #[Route\RequestMethod\Post]
    #[Route(path: self::PARAMETERS_PATH, name: self::PARAMETERS_NAME)]
    #[Route\Middleware(RouteDispatchedMiddlewareClass::class)]
    #[Route\Middleware(RouteMatchedMiddlewareClass::class)]
    #[Route\Middleware(SendingResponseMiddlewareClass::class)]
    #[Route\Middleware(TerminatedMiddlewareClass::class)]
    #[Route\Middleware(ThrowableCaughtMiddlewareClass::class)]
    #[Route\RequestStruct(IndexedJsonRequestStructEnum::class)]
    #[Route\ResponseStruct(ResponseStructEnum::class)]
    public function parameters(
        ResponseFactory $responseFactory,
        #[Parameter(name: self::PARAMETERS_PARAMETER_NAME, regex: Regex::ALPHA)]
        string $name,
    ): Response {
        return $responseFactory->createResponse(
            content: "parameters$name"
        );
    }
}
