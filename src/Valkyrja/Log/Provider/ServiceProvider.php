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
use Monolog\Logger;
use Override;
use Psr\Log\LoggerInterface;
use Valkyrja\Application\Env\Env;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Log\Enum\LogLevel;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Log\Logger\NullLogger;
use Valkyrja\Log\Logger\PsrLogger;
use Valkyrja\Support\Directory\Directory;

use function date;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            LoggerContract::class  => [self::class, 'publishLogger'],
            PsrLogger::class       => [self::class, 'publishPsrLogger'],
            NullLogger::class      => [self::class, 'publishNullLogger'],
            LoggerInterface::class => [self::class, 'publishLoggerInterface'],
            Logger::class          => [self::class, 'publishMonolog'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            LoggerContract::class,
            NullLogger::class,
            PsrLogger::class,
            LoggerInterface::class,
            Logger::class,
        ];
    }

    /**
     * Publish the logger service.
     */
    public static function publishLogger(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<LoggerContract> $default */
        $default = $env::LOG_DEFAULT_LOGGER
            ?? PsrLogger::class;

        $container->setSingleton(
            LoggerContract::class,
            $container->getSingleton($default),
        );
    }

    /**
     * Publish the psr adapter service.
     */
    public static function publishPsrLogger(ContainerContract $container): void
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
     * @param ContainerContract $container The container
     */
    public static function publishNullLogger(ContainerContract $container): void
    {
        $container->setSingleton(
            NullLogger::class,
            new NullLogger(),
        );
    }

    /**
     * Publish the psr logger interface.
     */
    public static function publishLoggerInterface(ContainerContract $container): void
    {
        $container->setSingleton(
            LoggerInterface::class,
            $container->getSingleton(Logger::class),
        );
    }

    /**
     * Publish the Logger service.
     */
    public static function publishMonolog(ContainerContract $container): void
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
            Logger::class,
            new Logger(
                $name,
                [
                    $handler,
                ]
            )
        );
    }
}
