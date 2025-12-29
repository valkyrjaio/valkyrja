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

namespace Valkyrja\Log\Provider;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as Monolog;
use Override;
use Psr\Log\LoggerInterface;
use Valkyrja\Application\Env;
use Valkyrja\Container\Manager\Contract\Container;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Log\Enum\LogLevel;
use Valkyrja\Log\Logger\Contract\Logger;
use Valkyrja\Log\Logger\NullLogger;
use Valkyrja\Log\Logger\PsrLogger;
use Valkyrja\Support\Directory;

use function date;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            Logger::class          => [self::class, 'publishLogger'],
            PsrLogger::class       => [self::class, 'publishPsrLogger'],
            NullLogger::class      => [self::class, 'publishNullLogger'],
            LoggerInterface::class => [self::class, 'publishLoggerInterface'],
            Monolog::class         => [self::class, 'publishMonolog'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            Logger::class,
            NullLogger::class,
            PsrLogger::class,
            LoggerInterface::class,
            Monolog::class,
        ];
    }

    /**
     * Publish the logger service.
     */
    public static function publishLogger(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<Logger> $default */
        $default = $env::LOG_DEFAULT_LOGGER;

        $container->setSingleton(
            Logger::class,
            $container->getSingleton($default),
        );
    }

    /**
     * Publish the psr adapter service.
     */
    public static function publishPsrLogger(Container $container): void
    {
        $container->setSingleton(
            PsrLogger::class,
            new PsrLogger(
                $container->getSingleton(LoggerInterface::class),
            ),
        );
    }

    /**
     * Publish the null adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishNullLogger(Container $container): void
    {
        $container->setSingleton(
            NullLogger::class,
            new NullLogger(),
        );
    }

    /**
     * Publish the psr logger interface.
     */
    public static function publishLoggerInterface(Container $container): void
    {
        $container->setSingleton(
            LoggerInterface::class,
            $container->getSingleton(Monolog::class),
        );
    }

    /**
     * Publish the Monolog service.
     */
    public static function publishMonolog(Container $container): void
    {
        $filePath = Directory::logsStoragePath();
        $name     = 'valkyrja' . date('-Y-m-d');

        $handler = new StreamHandler(
            "$filePath/$name.log",
            LogLevel::DEBUG->name
        );

        $formatter = new LineFormatter(
            null,
            null,
            true,
            true
        );

        $handler->setFormatter($formatter);

        $container->setSingleton(
            Monolog::class,
            new Monolog(
                $name,
                [
                    $handler,
                ]
            )
        );
    }
}
