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

namespace Valkyrja\Path\Provider;

use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Path\Generator\Contract\Generator;
use Valkyrja\Path\Parser\Contract\Parser;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            Generator::class => [self::class, 'publishGenerator'],
            Parser::class    => [self::class, 'publishParser'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Generator::class,
            Parser::class,
        ];
    }

    /**
     * Publish the generator service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishGenerator(Container $container): void
    {
        $container->setSingleton(
            Generator::class,
            new \Valkyrja\Path\Generator\Generator()
        );
    }

    /**
     * Publish the parser service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishParser(Container $container): void
    {
        /** @var array{path: \Valkyrja\Path\Config|array<string, mixed>, ...} $config */
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Parser::class,
            new \Valkyrja\Path\Parser\Parser(
                $config['path']
            )
        );
    }
}
