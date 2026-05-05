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

namespace Valkyrja\Tests\Unit\View\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Extension\DebugExtension;
use Twig\Extension\ExtensionInterface;
use Valkyrja\Application\Env\Env;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\Tests\EnvClass;
use Valkyrja\View\Factory\Contract\ViewResponseFactoryContract;
use Valkyrja\View\Factory\ViewResponseFactory;
use Valkyrja\View\Provider\ViewServiceProvider;
use Valkyrja\View\Renderer\Contract\RendererContract;
use Valkyrja\View\Renderer\OrkaRenderer;
use Valkyrja\View\Renderer\PhpRenderer;
use Valkyrja\View\Renderer\TwigRenderer;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ViewServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(RendererContract::class, ViewServiceProvider::publishers());
        self::assertArrayHasKey(PhpRenderer::class, ViewServiceProvider::publishers());
        self::assertArrayHasKey(OrkaRenderer::class, ViewServiceProvider::publishers());
        self::assertArrayHasKey(TwigRenderer::class, ViewServiceProvider::publishers());
        self::assertArrayHasKey(Environment::class, ViewServiceProvider::publishers());
        self::assertArrayHasKey(ViewResponseFactoryContract::class, ViewServiceProvider::publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishRenderer(): void
    {
        $this->container->setSingleton(PhpRenderer::class, self::createStub(PhpRenderer::class));

        $callback = ViewServiceProvider::publishers()[RendererContract::class];
        $callback($this->container);

        self::assertInstanceOf(PhpRenderer::class, $this->container->getSingleton(RendererContract::class));
    }

    public function testPublishPhpRenderer(): void
    {
        $callback = ViewServiceProvider::publishers()[PhpRenderer::class];
        $callback($this->container);

        self::assertInstanceOf(PhpRenderer::class, $this->container->getSingleton(PhpRenderer::class));
    }

    public function testPublishOrkaRenderer(): void
    {
        $callback = ViewServiceProvider::publishers()[OrkaRenderer::class];
        $callback($this->container);

        self::assertInstanceOf(OrkaRenderer::class, $this->container->getSingleton(OrkaRenderer::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishTwigRenderer(): void
    {
        $this->container->setSingleton(Environment::class, self::createStub(Environment::class));

        $callback = ViewServiceProvider::publishers()[TwigRenderer::class];
        $callback($this->container);

        self::assertInstanceOf(TwigRenderer::class, $this->container->getSingleton(TwigRenderer::class));
    }

    /**
     * @throws LoaderError
     */
    public function testPublishTwigEnvironment(): void
    {
        $this->container->setSingleton(Env::class, self::createStub(Env::class));

        $callback = ViewServiceProvider::publishers()[Environment::class];
        $callback($this->container);

        self::assertInstanceOf(Environment::class, $this->container->getSingleton(Environment::class));
    }

    /**
     * @throws LoaderError
     */
    public function testPublishTwigEnvironmentWithCustomEnv(): void
    {
        $this->container->setSingleton(
            Env::class,
            new class extends Env {
                /** @var array<string, string> */
                public const array VIEW_TWIG_PATHS = [
                    'namespace' => EnvClass::APP_DIR . '/storage',
                ];
                /** @var class-string<ExtensionInterface>[] */
                public const array VIEW_TWIG_EXTENSIONS = [
                    DebugExtension::class,
                ];
            }
        );

        $callback = ViewServiceProvider::publishers()[Environment::class];
        $callback($this->container);

        self::assertInstanceOf(Environment::class, $this->container->getSingleton(Environment::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishResponseFactory(): void
    {
        $this->container->setSingleton(ResponseFactoryContract::class, self::createStub(ResponseFactoryContract::class));
        $this->container->setSingleton(RendererContract::class, self::createStub(RendererContract::class));

        $callback = ViewServiceProvider::publishers()[ViewResponseFactoryContract::class];
        $callback($this->container);

        self::assertInstanceOf(ViewResponseFactory::class, $this->container->getSingleton(ViewResponseFactoryContract::class));
    }
}
