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

namespace Valkyrja\View\Provider;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;
use Valkyrja\Application\Config\ValkyrjaConfig;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory as HttpMessageResponseFactory;
use Valkyrja\View\Config\OrkaConfiguration;
use Valkyrja\View\Config\PhpConfiguration;
use Valkyrja\View\Config\TwigConfiguration;
use Valkyrja\View\Contract\View;
use Valkyrja\View\Engine\Contract\Engine;
use Valkyrja\View\Engine\OrkaEngine;
use Valkyrja\View\Engine\PhpEngine;
use Valkyrja\View\Engine\TwigEngine;
use Valkyrja\View\Factory\ContainerFactory;
use Valkyrja\View\Factory\Contract\Factory;
use Valkyrja\View\Factory\Contract\ResponseFactory;
use Valkyrja\View\Template\Contract\Template;

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
            View::class            => [self::class, 'publishView'],
            Factory::class         => [self::class, 'publishFactory'],
            Template::class        => [self::class, 'publishTemplate'],
            PhpEngine::class       => [self::class, 'publishPhpEngine'],
            OrkaEngine::class      => [self::class, 'publishOrkaEngine'],
            TwigEngine::class      => [self::class, 'publishTwigEngine'],
            Environment::class     => [self::class, 'publishTwigEnvironment'],
            ResponseFactory::class => [self::class, 'publishResponseFactory'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            View::class,
            Factory::class,
            Template::class,
            PhpEngine::class,
            OrkaEngine::class,
            TwigEngine::class,
            Environment::class,
            ResponseFactory::class,
        ];
    }

    /**
     * Publish the view service.
     */
    public static function publishView(Container $container): void
    {
        $config = $container->getSingleton(ValkyrjaConfig::class);

        $container->setSingleton(
            View::class,
            new \Valkyrja\View\View(
                $container,
                $container->getSingleton(Factory::class),
                $config->view
            )
        );
    }

    /**
     * Publish the factory service.
     */
    public static function publishFactory(Container $container): void
    {
        $container->setSingleton(
            Factory::class,
            new ContainerFactory($container)
        );
    }

    /**
     * Publish the template service.
     */
    public static function publishTemplate(Container $container): void
    {
        $container->setCallable(
            Template::class,
            [self::class, 'createTemplate']
        );
    }

    /**
     * Create a template.
     */
    public static function createTemplate(Container $container, Engine $engine, string $name): Template
    {
        return new \Valkyrja\View\Template\Template($engine, $name);
    }

    /**
     * Publish the PHP engine service.
     */
    public static function publishPhpEngine(Container $container): void
    {
        $container->setCallable(
            PhpEngine::class,
            [self::class, 'createPhpEngine']
        );
    }

    /**
     * Create a Php engine.
     */
    public static function createPhpEngine(Container $container, PhpConfiguration $config): PhpEngine
    {
        return new PhpEngine($config);
    }

    /**
     * Publish the Orka engine service.
     */
    public static function publishOrkaEngine(Container $container): void
    {
        $container->setCallable(
            OrkaEngine::class,
            [self::class, 'createOrkaEngine']
        );
    }

    /**
     * Create an Orka engine.
     */
    public static function createOrkaEngine(Container $container, OrkaConfiguration $config): OrkaEngine
    {
        return new OrkaEngine($config);
    }

    /**
     * Publish the Twig engine service.
     */
    public static function publishTwigEngine(Container $container): void
    {
        $container->setCallable(
            TwigEngine::class,
            [self::class, 'createTwigEngine']
        );
    }

    /**
     * Create a Twig engine.
     */
    public static function createTwigEngine(Container $container, TwigConfiguration $config): TwigEngine
    {
        return new TwigEngine(
            $container->get(Environment::class, [$config])
        );
    }

    /**
     * Publish the response factory service.
     */
    public static function publishResponseFactory(Container $container): void
    {
        $container->setSingleton(
            ResponseFactory::class,
            new \Valkyrja\View\Factory\ResponseFactory(
                $container->getSingleton(HttpMessageResponseFactory::class),
                $container->getSingleton(View::class)
            )
        );
    }

    /**
     * Publish the Twig environment service.
     */
    public static function publishTwigEnvironment(Container $container): void
    {
        // Set the twig environment as a singleton in the container
        $container->setCallable(
            Environment::class,
            [self::class, 'createTwigEnvironment']
        );
    }

    /**
     * Create a Twig environment.
     *
     * @throws LoaderError
     */
    public static function createTwigEnvironment(Container $container, TwigConfiguration $config): Environment
    {
        $globalConfig = $container->getSingleton(ValkyrjaConfig::class);
        $debug        = $globalConfig->app->debugMode;
        $paths        = $config->paths;
        $extensions   = $config->extensions;
        $compiledDir  = $config->compiledDir;

        // Get the twig filesystem loader
        $loader = new FilesystemLoader();

        // Iterate through the dirs and add each as a path in the twig loader
        foreach ($paths as $namespace => $dir) {
            $loader->addPath($dir, $namespace);
        }

        // Create a new twig environment
        $twig = new Environment(
            $loader,
            [
                'cache'   => $compiledDir,
                'debug'   => $debug,
                'charset' => 'utf-8',
            ]
        );

        // Iterate through the extensions
        foreach ($extensions as $extension) {
            // And add each extension to the twig environment
            $twig->addExtension(new $extension());
        }

        return $twig;
    }
}
