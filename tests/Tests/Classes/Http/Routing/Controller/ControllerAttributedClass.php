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

use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Routing\Attribute\Route;
use Valkyrja\Http\Routing\Attribute\Route\Name;
use Valkyrja\Http\Routing\Attribute\Route\Path;

/**
 * Controller class to test routes.
 */
#[Path('/controller')]
#[Name('controller')]
final class ControllerAttributedClass
{
    /** @var non-empty-string */
    public const string WELCOME_PATH = '/welcome';
    /** @var non-empty-string */
    public const string WELCOME_NAME = 'welcome';

    /**
     * Handler for the welcome route.
     *
     * @param array<array-key, mixed> $arguments The arguments
     */
    public static function welcomeHandler(ContainerContract $container, array $arguments): ResponseContract
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
