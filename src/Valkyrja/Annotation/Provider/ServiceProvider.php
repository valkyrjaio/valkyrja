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

namespace Valkyrja\Annotation\Provider;

use Valkyrja\Annotation\Contract\Annotations;
use Valkyrja\Annotation\Filter\Contract\Filter;
use Valkyrja\Annotation\Parser\Contract\Parser;
use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Reflection\Contract\Reflection;

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
    public static function publishers(): array
    {
        return [
            Annotations::class => [self::class, 'publishAnnotator'],
            Filter::class      => [self::class, 'publishFilter'],
            Parser::class      => [self::class, 'publishParser'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Annotations::class,
            Filter::class,
            Parser::class,
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
            new \Valkyrja\Annotation\Annotations(
                $container->getSingleton(Parser::class),
                $container->getSingleton(Reflection::class)
            )
        );
    }

    /**
     * Publish the filter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishFilter(Container $container): void
    {
        $container->setSingleton(
            Filter::class,
            new \Valkyrja\Annotation\Filter\Filter(
                $container->getSingleton(Annotations::class)
            )
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
        /** @var array{annotation: \Valkyrja\Annotation\Config|array<string, mixed>, ...} $config */
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Parser::class,
            new \Valkyrja\Annotation\Parser\Parser(
                $config['annotation']
            )
        );
    }
}
