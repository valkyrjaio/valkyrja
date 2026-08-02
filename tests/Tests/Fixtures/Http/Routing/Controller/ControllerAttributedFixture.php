<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Http\Routing\Controller;

use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Routing\Attribute\Route;
use Valkyrja\Http\Routing\Attribute\Route\Name;
use Valkyrja\Http\Routing\Attribute\Route\Path;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

/**
 * Controller class to test routes.
 */
#[Path('/controller')]
#[Name('controller')]
final class ControllerAttributedFixture
{
    /** @var non-empty-string */
    public const string WELCOME_PATH = '/welcome';
    /** @var non-empty-string */
    public const string WELCOME_NAME = 'welcome';

    public static function welcomeHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        $controller = new self();

        return $controller->welcome();
    }

    #[Path('/path')]
    #[Name('name')]
    #[Route(path: self::WELCOME_PATH, name: self::WELCOME_NAME, handler: [self::class, 'welcomeHandler'])]
    public function welcome(): ResponseContract
    {
        return Response::create('welcome');
    }
}
