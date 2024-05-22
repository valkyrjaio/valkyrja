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

namespace Valkyrja\Validation\Provider;

use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Orm\Orm as ORMManager;
use Valkyrja\Validation\Contract\Validation;
use Valkyrja\Validation\Factory\ContainerFactory;
use Valkyrja\Validation\Factory\Contract\Factory;
use Valkyrja\Validation\Rule\ORM;

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
            Validation::class => [self::class, 'publishValidator'],
            Factory::class    => [self::class, 'publishFactory'],
            ORM::class        => [self::class, 'publishOrmRules'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Validation::class,
            Factory::class,
            ORM::class,
        ];
    }

    /**
     * Publish the validator service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishValidator(Container $container): void
    {
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Validation::class,
            new \Valkyrja\Validation\Validation(
                $container->getSingleton(Factory::class),
                $config['validation']
            )
        );
    }

    /**
     * Publish the factory service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishFactory(Container $container): void
    {
        $container->setSingleton(
            Factory::class,
            new ContainerFactory(
                $container,
            )
        );
    }

    /**
     * Publish the ORM rules service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishOrmRules(Container $container): void
    {
        $container->setSingleton(
            ORM::class,
            new ORM(
                $container,
                $container->getSingleton(ORMManager::class)
            )
        );
    }
}
