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

namespace Valkyrja\View\Providers;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Twig\TwigEngine;
use Valkyrja\View\Engines\PHPEngine;
use Valkyrja\View\Exceptions\InvalidConfigPath;
use Valkyrja\View\View;

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
            View::class        => 'publishView',
            PHPEngine::class   => 'publishPHPEngine',
            TwigEngine::class  => 'publishTwigEngine',
            Environment::class => 'publishTwigEnvironment',
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
            View::class,
            PHPEngine::class,
            TwigEngine::class,
            Environment::class,
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
     * Publish the view service.
     *
     * @param Container $container The container
     *
     * @throws InvalidConfigPath
     *
     * @return void
     */
    public static function publishView(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            View::class,
            new \Valkyrja\View\Managers\View(
                $container,
                (array) $config['view']
            )
        );
    }

    /**
     * Publish the PHP engine service.
     *
     * @param Container $container The container
     *
     * @throws InvalidConfigPath
     *
     * @return void
     */
    public static function publishPHPEngine(Container $container): void
    {
        $container->setSingleton(
            PHPEngine::class,
            new PHPEngine(
                $container->getSingleton(View::class)
            )
        );
    }

    /**
     * Publish the Twig engine service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishTwigEngine(Container $container): void
    {
        $container->setSingleton(
            TwigEngine::class,
            new TwigEngine(
                $container->getSingleton(Environment::class)
            )
        );
    }

    /**
     * Publish the Twig environment service.
     *
     * @param Container $container The container
     *
     * @throws InvalidConfigPath
     * @throws LoaderError
     *
     * @return void
     */
    public static function publishTwigEnvironment(Container $container): void
    {
        $config     = $container->getSingleton('config');
        $viewConfig = $config['view'];
        $twigConfig = $viewConfig['disks']['twig'];

        // Get the twig filesystem loader
        $loader = new FilesystemLoader();

        // Iterate through the dirs and add each as a path in the twig loader
        foreach ($viewConfig['paths'] as $namespace => $dir) {
            $loader->addPath($dir, $namespace);
        }

        // Create a new twig environment
        $twig = new Environment(
            $loader,
            [
                'cache'   => $twigConfig['compiledDir'],
                'debug'   => $config['app']['debug'],
                'charset' => 'utf-8',
            ]
        );

        // Iterate through the extensions
        foreach ($twigConfig['extensions'] as $extension) {
            // And add each extension to the twig environment
            $twig->addExtension(new $extension());
        }

        // Set the twig environment as a singleton in the container
        $container->setSingleton(
            Environment::class,
            $twig
        );
    }
}
