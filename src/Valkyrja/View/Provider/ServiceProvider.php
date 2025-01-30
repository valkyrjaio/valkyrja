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
use Twig\Extension\ExtensionInterface;
use Twig\Loader\FilesystemLoader;
use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory as HttpMessageResponseFactory;
use Valkyrja\View\Contract\View;
use Valkyrja\View\Engine\Contract\Engine;
use Valkyrja\View\Engine\OrkaEngine;
use Valkyrja\View\Engine\PhpEngine;
use Valkyrja\View\Engine\TwigEngine;
use Valkyrja\View\Exception\InvalidConfigPath;
use Valkyrja\View\Factory\ContainerFactory;
use Valkyrja\View\Factory\Contract\Factory;
use Valkyrja\View\Factory\Contract\ResponseFactory;
use Valkyrja\View\Template\Contract\Template;

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
        ];
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
        /** @var Config $config */
        $config = $container->getSingleton(Config::class);
        /** @var \Valkyrja\View\Config $viewConfig */
        $viewConfig = $config['view'];

        $container->setSingleton(
            View::class,
            new \Valkyrja\View\View(
                $container,
                $container->getSingleton(Factory::class),
                $viewConfig
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
            new ContainerFactory($container)
        );
    }

    /**
     * Publish the template service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishTemplate(Container $container): void
    {
        $container->setCallable(
            Template::class,
            static fn (Engine $engine) => new \Valkyrja\View\Template\Template($engine)
        );
    }

    /**
     * Publish the PHP engine service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPhpEngine(Container $container): void
    {
        /** @var Config $config */
        $config = $container->getSingleton(Config::class);
        /** @var \Valkyrja\View\Config $viewConfig */
        $viewConfig = $config['view'];

        $container->setSingleton(
            PhpEngine::class,
            new PhpEngine($viewConfig)
        );
    }

    /**
     * Publish the Orka engine service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishOrkaEngine(Container $container): void
    {
        /** @var Config $config */
        $config = $container->getSingleton(Config::class);
        /** @var \Valkyrja\Application\Config $appConfig */
        $appConfig = $config['app'];
        /** @var bool $debug */
        $debug = $appConfig['debug'];
        /** @var \Valkyrja\View\Config $viewConfig */
        $viewConfig = $config['view'];

        $container->setSingleton(
            OrkaEngine::class,
            new OrkaEngine($viewConfig, $debug)
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
     * Publish the response factory service.
     *
     * @param Container $container The container
     *
     * @return void
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
     *
     * @param Container $container The container
     *
     * @throws LoaderError
     *
     * @return void
     */
    public static function publishTwigEnvironment(Container $container): void
    {
        /** @var Config $config */
        $config = $container->getSingleton(Config::class);
        /** @var \Valkyrja\Application\Config $appConfig */
        $appConfig = $config['app'];
        /** @var bool $debug */
        $debug = $appConfig['debug'];
        /** @var \Valkyrja\View\Config $viewConfig */
        $viewConfig = $config['view'];
        /** @var array<string, mixed> $disks */
        $disks = $viewConfig['engines'];
        /** @var array<string, mixed> $twigConfig */
        $twigConfig = $disks['twig'];
        /** @var array<string, string> $paths */
        $paths = $twigConfig['paths'];
        /** @var class-string<ExtensionInterface>[] $extensions */
        $extensions = $twigConfig['extensions'];
        /** @var string $compiledDir */
        $compiledDir = $twigConfig['compiledDir'];

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

        // Set the twig environment as a singleton in the container
        $container->setSingleton(
            Environment::class,
            $twig
        );
    }
}
