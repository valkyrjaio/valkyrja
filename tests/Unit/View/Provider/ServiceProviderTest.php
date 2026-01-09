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
use Valkyrja\Http\Message\Factory\Contract\ResponseFactoryContract as HttpMessageResponseFactory;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;
use Valkyrja\View\Factory\Contract\ResponseFactoryContract;
use Valkyrja\View\Factory\ResponseFactory;
use Valkyrja\View\Provider\ServiceProvider;
use Valkyrja\View\Renderer\Contract\RendererContract;
use Valkyrja\View\Renderer\OrkaRenderer;
use Valkyrja\View\Renderer\PhpRenderer;
use Valkyrja\View\Renderer\TwigRenderer;

/**
 * Test the ServiceProvider.
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(RendererContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(PhpRenderer::class, ServiceProvider::publishers());
        self::assertArrayHasKey(OrkaRenderer::class, ServiceProvider::publishers());
        self::assertArrayHasKey(TwigRenderer::class, ServiceProvider::publishers());
        self::assertArrayHasKey(Environment::class, ServiceProvider::publishers());
        self::assertArrayHasKey(ResponseFactoryContract::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(RendererContract::class, ServiceProvider::provides());
        self::assertContains(PhpRenderer::class, ServiceProvider::provides());
        self::assertContains(OrkaRenderer::class, ServiceProvider::provides());
        self::assertContains(TwigRenderer::class, ServiceProvider::provides());
        self::assertContains(Environment::class, ServiceProvider::provides());
        self::assertContains(ResponseFactoryContract::class, ServiceProvider::provides());
    }

    /**
     * @throws Exception
     */
    public function testPublishRenderer(): void
    {
        $this->container->setSingleton(PhpRenderer::class, self::createStub(PhpRenderer::class));

        $callback = ServiceProvider::publishers()[RendererContract::class];
        $callback($this->container);

        self::assertInstanceOf(PhpRenderer::class, $this->container->getSingleton(RendererContract::class));
    }

    public function testPublishPhpRenderer(): void
    {
        $callback = ServiceProvider::publishers()[PhpRenderer::class];
        $callback($this->container);

        self::assertInstanceOf(PhpRenderer::class, $this->container->getSingleton(PhpRenderer::class));
    }

    public function testPublishOrkaRenderer(): void
    {
        $callback = ServiceProvider::publishers()[OrkaRenderer::class];
        $callback($this->container);

        self::assertInstanceOf(OrkaRenderer::class, $this->container->getSingleton(OrkaRenderer::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishTwigRenderer(): void
    {
        $this->container->setSingleton(Environment::class, self::createStub(Environment::class));

        $callback = ServiceProvider::publishers()[TwigRenderer::class];
        $callback($this->container);

        self::assertInstanceOf(TwigRenderer::class, $this->container->getSingleton(TwigRenderer::class));
    }

    /**
     * @throws LoaderError
     */
    public function testPublishTwigEnvironment(): void
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

        $callback = ServiceProvider::publishers()[Environment::class];
        $callback($this->container);

        self::assertInstanceOf(Environment::class, $this->container->getSingleton(Environment::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishResponseFactory(): void
    {
        $this->container->setSingleton(HttpMessageResponseFactory::class, self::createStub(HttpMessageResponseFactory::class));
        $this->container->setSingleton(RendererContract::class, self::createStub(RendererContract::class));

        $callback = ServiceProvider::publishers()[ResponseFactoryContract::class];
        $callback($this->container);

        self::assertInstanceOf(ResponseFactory::class, $this->container->getSingleton(ResponseFactoryContract::class));
    }
}
