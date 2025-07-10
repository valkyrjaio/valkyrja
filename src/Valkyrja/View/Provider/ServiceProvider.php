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

use Override;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Extension\ExtensionInterface as TwigExtensionInterface;
use Twig\Loader\FilesystemLoader;
use Valkyrja\Application\Env;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory as HttpMessageResponseFactory;
use Valkyrja\View\Contract\Renderer;
use Valkyrja\View\Factory\Contract\ResponseFactory;
use Valkyrja\View\OrkaRenderer;
use Valkyrja\View\PhpRenderer;
use Valkyrja\View\TwigRenderer;

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
            Renderer::class        => [self::class, 'publishRenderer'],
            PhpRenderer::class     => [self::class, 'publishPhpRenderer'],
            OrkaRenderer::class    => [self::class, 'publishOrkaRenderer'],
            TwigRenderer::class    => [self::class, 'publishTwigRenderer'],
            Environment::class     => [self::class, 'publishTwigEnvironment'],
            ResponseFactory::class => [self::class, 'publishResponseFactory'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            Renderer::class,
            PhpRenderer::class,
            OrkaRenderer::class,
            TwigRenderer::class,
            Environment::class,
            ResponseFactory::class,
        ];
    }

    /**
     * Publish the renderer service.
     */
    public static function publishRenderer(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<Renderer> $default */
        $default = $env::VIEW_DEFAULT_RENDERER;

        $container->setSingleton(
            Renderer::class,
            $container->getSingleton($default)
        );
    }

    /**
     * Publish the renderer service.
     */
    public static function publishPhpRenderer(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string $dir */
        $dir = $env::VIEW_PHP_DIR;
        /** @var non-empty-string $fileExtension */
        $fileExtension = $env::VIEW_PHP_FILE_EXTENSION;
        /** @var array<string, string> $paths */
        $paths = $env::VIEW_PHP_PATHS;

        $container->setSingleton(
            PhpRenderer::class,
            new PhpRenderer(
                dir: $dir,
                fileExtension: $fileExtension,
                paths: $paths
            ),
        );
    }

    /**
     * Publish the renderer service.
     */
    public static function publishOrkaRenderer(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var bool $debug */
        $debug = $env::APP_DEBUG_MODE;
        /** @var non-empty-string $dir */
        $dir = $env::VIEW_ORKA_DIR;
        /** @var non-empty-string $fileExtension */
        $fileExtension = $env::VIEW_ORKA_FILE_EXTENSION;
        /** @var array<string, string> $paths */
        $paths = $env::VIEW_ORKA_PATHS;

        $container->setSingleton(
            OrkaRenderer::class,
            new OrkaRenderer(
                dir: $dir,
                fileExtension: $fileExtension,
                paths: $paths,
                debug: $debug
            ),
        );
    }

    /**
     * Publish the renderer service.
     */
    public static function publishTwigRenderer(Container $container): void
    {
        $container->setSingleton(
            TwigRenderer::class,
            new TwigRenderer(
                $container->getSingleton(Environment::class),
            ),
        );
    }

    /**
     * Publish the renderer service.
     *
     * @throws LoaderError
     */
    public static function publishTwigEnvironment(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var bool $debug */
        $debug = $env::APP_DEBUG_MODE;
        /** @var array<string, string> $paths */
        $paths = $env::VIEW_TWIG_PATHS;
        /** @var class-string<TwigExtensionInterface>[] $extensions */
        $extensions = $env::VIEW_TWIG_EXTENSIONS;
        /** @var non-empty-string $compiledDir */
        $compiledDir = $env::VIEW_TWIG_COMPILED_DIR;

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

        $container->setSingleton(
            Environment::class,
            $twig,
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
                $container->getSingleton(Renderer::class)
            )
        );
    }
}
