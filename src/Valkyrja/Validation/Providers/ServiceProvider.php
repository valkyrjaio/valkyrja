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

namespace Valkyrja\Validation\Providers;

use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\ORM\ORM as ORMManager;
use Valkyrja\Validation\Rules\ORM;
use Valkyrja\Validation\Validator;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function publishers(): array
    {
        return [
            Validator::class => 'publishValidator',
            ORM::class       => 'publishORM',
        ];
    }

    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function provides(): array
    {
        return [
            Validator::class,
            ORM::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
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
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Validator::class,
            new \Valkyrja\Validation\Validators\Validator(
                $container,
                $config['validation']
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
    public static function publishORM(Container $container): void
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
