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

namespace Valkyrja\Tests\Unit\View\Renderer;

use PHPUnit\Framework\MockObject\Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Renderer\Contract\RendererContract;
use Valkyrja\View\Renderer\TwigRenderer;
use Valkyrja\View\Template\Template;

/**
 * Test the TwigRenderer class.
 */
class TwigRendererTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testImplementsContract(): void
    {
        $twig     = self::createStub(Environment::class);
        $renderer = new TwigRenderer($twig);

        self::assertInstanceOf(RendererContract::class, $renderer);
    }

    /**
     * @throws Exception
     */
    public function testStartRender(): void
    {
        $twig     = self::createStub(Environment::class);
        $renderer = new TwigRenderer($twig);

        $renderer->startRender();

        $this->addToAssertionCount(1);
    }

    /**
     * @throws Exception
     */
    public function testEndRender(): void
    {
        $twig     = self::createStub(Environment::class);
        $renderer = new TwigRenderer($twig);

        self::assertSame('', $renderer->endRender());
    }

    /**
     * @throws Exception
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testRender(): void
    {
        $templateName    = 'home.twig';
        $variables       = ['title' => 'Home Page'];
        $renderedContent = '<html lang="en"><body>Home Page</body></html>';

        $twig = $this->createMock(Environment::class);
        $twig->expects($this->once())
             ->method('render')
             ->with($templateName, $variables)
             ->willReturn($renderedContent);

        $renderer = new TwigRenderer($twig);

        self::assertSame($renderedContent, $renderer->render($templateName, $variables));
    }

    /**
     * @throws Exception
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testRenderWithoutVariables(): void
    {
        $templateName    = 'home.twig';
        $renderedContent = '<html lang="en"><body>Home</body></html>';

        $twig = $this->createMock(Environment::class);
        $twig->expects($this->once())
             ->method('render')
             ->with($templateName, [])
             ->willReturn($renderedContent);

        $renderer = new TwigRenderer($twig);

        self::assertSame($renderedContent, $renderer->render($templateName));
    }

    /**
     * @throws Exception
     */
    public function testCreateTemplate(): void
    {
        $templateName = 'page.twig';
        $variables    = ['content' => 'Test content'];

        $twig     = self::createStub(Environment::class);
        $renderer = new TwigRenderer($twig);

        $template = $renderer->createTemplate($templateName, $variables);

        self::assertSame($templateName, $template->getName());
        self::assertSame($variables, $template->getVariables());
    }

    /**
     * @throws Exception
     */
    public function testCreateTemplateWithoutVariables(): void
    {
        $templateName = 'page.twig';

        $twig     = self::createStub(Environment::class);
        $renderer = new TwigRenderer($twig);

        $template = $renderer->createTemplate($templateName);

        self::assertInstanceOf(Template::class, $template);
        self::assertSame($templateName, $template->getName());
        self::assertSame([], $template->getVariables());
    }

    /**
     * @throws Exception
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testRenderFile(): void
    {
        $templateName    = 'partials/header.twig';
        $variables       = ['user' => 'John'];
        $renderedContent = '<header>Welcome, John</header>';

        $twig = $this->createMock(Environment::class);
        $twig->expects($this->once())
             ->method('render')
             ->with($templateName, $variables)
             ->willReturn($renderedContent);

        $renderer = new TwigRenderer($twig);

        self::assertSame($renderedContent, $renderer->renderFile($templateName, $variables));
    }

    /**
     * @throws Exception
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testRenderFileWithoutVariables(): void
    {
        $templateName    = 'partials/footer.twig';
        $renderedContent = '<footer>Copyright 2024</footer>';

        $twig = $this->createMock(Environment::class);
        $twig->expects($this->once())
             ->method('render')
             ->with($templateName, [])
             ->willReturn($renderedContent);

        $renderer = new TwigRenderer($twig);

        self::assertSame($renderedContent, $renderer->renderFile($templateName));
    }
}
