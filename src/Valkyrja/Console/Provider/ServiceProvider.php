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

namespace Valkyrja\Console\Provider;

use Valkyrja\Annotation\Filter\Contract\Filter;
use Valkyrja\Config\Config\Config;
use Valkyrja\Console\Annotation\Contract\Annotations;
use Valkyrja\Console\CacheableConsole;
use Valkyrja\Console\Contract\Console;
use Valkyrja\Console\Formatter\Contract\Formatter;
use Valkyrja\Console\Input\Contract\Input;
use Valkyrja\Console\Kernel\Contract\Kernel;
use Valkyrja\Console\Output\Contract\Output;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Dispatcher\Contract\Dispatcher;
use Valkyrja\Dispatcher\Validator\Contract\Validator;
use Valkyrja\Event\Dispatcher as Events;
use Valkyrja\Path\Parser\Contract\Parser;
use Valkyrja\Reflection\Contract\Reflection;

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
            Annotations::class => [self::class, 'publishAnnotator'],
            Console::class     => [self::class, 'publishConsole'],
            Formatter::class   => [self::class, 'publishFormatter'],
            Input::class       => [self::class, 'publishInput'],
            Kernel::class      => [self::class, 'publishKernel'],
            Output::class      => [self::class, 'publishOutput'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Annotations::class,
            Console::class,
            Formatter::class,
            Input::class,
            Kernel::class,
            Output::class,
        ];
    }

    /**
     * Publish the annotator service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishAnnotator(Container $container): void
    {
        $container->setSingleton(
            Annotations::class,
            new \Valkyrja\Console\Annotation\Annotations(
                $container->getSingleton(Filter::class),
                $container->getSingleton(Reflection::class)
            )
        );
    }

    /**
     * Publish the console service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishConsole(Container $container): void
    {
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Console::class,
            $console = new CacheableConsole(
                $container,
                $container->getSingleton(Dispatcher::class),
                $container->getSingleton(Validator::class),
                $container->getSingleton(Events::class),
                $container->getSingleton(Parser::class),
                $config['console'],
                $config['app']['debug'],
            )
        );

        $console->setup();
    }

    /**
     * Publish the formatter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishFormatter(Container $container): void
    {
        $container->setSingleton(
            Formatter::class,
            new \Valkyrja\Console\Formatter\Formatter()
        );
    }

    /**
     * Publish the input service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishInput(Container $container): void
    {
        $arguments = $_SERVER['argv'] ?? [];

        // Strip the application name
        array_shift($arguments);

        $container->setSingleton(
            Input::class,
            new \Valkyrja\Console\Input\Input($arguments)
        );
    }

    /**
     * Publish the kernel service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishKernel(Container $container): void
    {
        $container->setSingleton(
            Kernel::class,
            new \Valkyrja\Console\Kernel\Kernel(
                $container->getSingleton(Console::class),
                $container,
                $container->getSingleton(Events::class)
            )
        );
    }

    /**
     * Publish the output service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishOutput(Container $container): void
    {
        $container->setSingleton(
            Output::class,
            new \Valkyrja\Console\Output\Output()
        );
    }
}
