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

use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Routing\Attribute\DynamicRoute;
use Valkyrja\Http\Routing\Attribute\Route;
use Valkyrja\Http\Routing\Attribute\Route\Middleware;
use Valkyrja\Http\Routing\Data\Parameter;
use Valkyrja\Tests\Classes\Http\Middleware\AllMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Struct\IndexedJsonRequestStructEnum;
use Valkyrja\Tests\Classes\Http\Struct\ResponseStructEnum;

/**
 * Controller class to test routes.
 */
final class ControllerWithAllMiddlewareClass
{
    /** @var non-empty-string */
    public const string WELCOME_PATH = '/';
    /** @var non-empty-string */
    public const string WELCOME_NAME = 'welcome';

    /** @var non-empty-string */
    public const string DYNAMIC_PATH = '/{dynamic}';
    /** @var non-empty-string */
    public const string DYNAMIC_NAME = 'dynamic';

    #[Route(
        path: self::WELCOME_PATH,
        name: self::WELCOME_NAME,
        requestStruct: IndexedJsonRequestStructEnum::first,
        responseStruct: ResponseStructEnum::first,
    )]
    #[Middleware(AllMiddlewareClass::class)]
    public function welcome(): ResponseContract
    {
        return Response::create('welcome');
    }

    #[DynamicRoute(
        path: self::DYNAMIC_PATH,
        name: self::DYNAMIC_NAME,
        parameters: [new Parameter('dynamic', regex: '/\d+/')],
        requestStruct: IndexedJsonRequestStructEnum::first,
        responseStruct: ResponseStructEnum::first,
    )]
    #[Middleware(AllMiddlewareClass::class)]
    public function welcomeDynamic(int $dynamic): ResponseContract
    {
        return Response::create("dynamic$dynamic");
    }
}
