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

namespace Valkyrja\Tests\Unit\View\Template;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Renderer\Contract\RendererContract;
use Valkyrja\View\Template\Contract\TemplateContract;
use Valkyrja\View\Template\Template;
use Valkyrja\View\Throwable\Exception\InvalidArgumentException;

/**
 * Test the Template class.
 */
final class TemplateTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testImplementsContract(): void
    {
        $renderer = self::createStub(RendererContract::class);
        $template = new Template($renderer, 'test');

        self::assertInstanceOf(TemplateContract::class, $template);
    }

    /**
     * @throws Exception
     */
    public function testGetName(): void
    {
        $renderer = self::createStub(RendererContract::class);
        $template = new Template($renderer, 'test-template');

        self::assertSame('test-template', $template->getName());
    }

    /**
     * @throws Exception
     */
    public function testSetName(): void
    {
        $renderer = self::createStub(RendererContract::class);
        $template = new Template($renderer, 'original');

        $result = $template->setName('updated');

        self::assertSame($template, $result);
        self::assertSame('updated', $template->getName());
    }

    /**
     * @throws Exception
     */
    public function testGetVariablesEmpty(): void
    {
        $renderer = self::createStub(RendererContract::class);
        $template = new Template($renderer, 'test');

        self::assertSame([], $template->getVariables());
    }

    /**
     * @throws Exception
     */
    public function testGetVariablesFromConstructor(): void
    {
        $renderer  = self::createStub(RendererContract::class);
        $variables = ['key' => 'value', 'foo' => 'bar'];
        $template  = new Template($renderer, 'test', $variables);

        self::assertSame($variables, $template->getVariables());
    }

    /**
     * @throws Exception
     */
    public function testSetVariables(): void
    {
        $renderer  = self::createStub(RendererContract::class);
        $template  = new Template($renderer, 'test', ['existing' => 'value']);
        $variables = ['new' => 'data'];

        $result = $template->setVariables($variables);

        self::assertSame($template, $result);
        self::assertSame(['existing' => 'value', 'new' => 'data'], $template->getVariables());
    }

    /**
     * @throws Exception
     */
    public function testSetVariablesMerges(): void
    {
        $renderer = self::createStub(RendererContract::class);
        $template = new Template($renderer, 'test', ['a' => '1', 'b' => '2']);

        $template->setVariables(['b' => 'updated', 'c' => '3']);

        self::assertSame(['a' => '1', 'b' => 'updated', 'c' => '3'], $template->getVariables());
    }

    /**
     * @throws Exception
     */
    public function testGetVariable(): void
    {
        $renderer = self::createStub(RendererContract::class);
        $template = new Template($renderer, 'test', ['key' => 'value']);

        self::assertSame('value', $template->getVariable('key'));
    }

    /**
     * @throws Exception
     */
    public function testGetVariableReturnsNullForMissing(): void
    {
        $renderer = self::createStub(RendererContract::class);
        $template = new Template($renderer, 'test');

        self::assertNull($template->getVariable('nonexistent'));
    }

    /**
     * @throws Exception
     */
    public function testSetVariable(): void
    {
        $renderer = self::createStub(RendererContract::class);
        $template = new Template($renderer, 'test');

        $result = $template->setVariable('key', 'value');

        self::assertSame($template, $result);
        self::assertSame('value', $template->getVariable('key'));
    }

    /**
     * @throws Exception
     */
    public function testEscapeString(): void
    {
        $renderer = self::createStub(RendererContract::class);
        $template = new Template($renderer, 'test');

        self::assertSame('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', $template->escape('<script>alert("xss")</script>'));
    }

    /**
     * @throws Exception
     */
    public function testEscapeInteger(): void
    {
        $renderer = self::createStub(RendererContract::class);
        $template = new Template($renderer, 'test');

        self::assertSame('123', $template->escape(123));
    }

    /**
     * @throws Exception
     */
    public function testEscapeFloat(): void
    {
        $renderer = self::createStub(RendererContract::class);
        $template = new Template($renderer, 'test');

        self::assertSame('3.14', $template->escape(3.14));
    }

    /**
     * @throws Exception
     */
    public function testEscapeHtmlEntities(): void
    {
        $renderer = self::createStub(RendererContract::class);
        $template = new Template($renderer, 'test');

        self::assertSame('&amp;', $template->escape('&'));
        self::assertSame('&#039;', $template->escape("'"));
        self::assertSame('&quot;', $template->escape('"'));
        self::assertSame('&lt;', $template->escape('<'));
        self::assertSame('&gt;', $template->escape('>'));
    }

    /**
     * @throws Exception
     */
    public function testWithoutLayout(): void
    {
        $templateContent = '<p>Content</p>';

        $renderer = $this->createMock(RendererContract::class);
        $renderer->expects($this->once())
            ->method('renderFile')
            ->willReturn($templateContent);

        $template = new Template($renderer, 'test');
        $template->setLayout('layouts/main');

        $result = $template->withoutLayout();

        self::assertSame($template, $result);
        self::assertSame($templateContent, $template->render());
    }

    /**
     * @throws Exception
     */
    public function testSetLayoutWithEmptyString(): void
    {
        $templateContent = '<p>Content</p>';

        $renderer = $this->createMock(RendererContract::class);
        $renderer->expects($this->once())
            ->method('renderFile')
            ->willReturn($templateContent);

        $template = new Template($renderer, 'test');
        $template->setLayout('layouts/main');

        $result = $template->setLayout('');

        self::assertSame($template, $result);
        self::assertSame($templateContent, $template->render());
    }

    /**
     * @throws Exception
     */
    public function testSetLayoutWithValue(): void
    {
        $templateContent = '<p>Content</p>';
        $layoutContent   = '<html lang="en"><body><p>Content</p></body></html>';

        $renderer = $this->createMock(RendererContract::class);
        $renderer->expects($this->exactly(2))
            ->method('renderFile')
            ->willReturnOnConsecutiveCalls($templateContent, $layoutContent);

        $template = new Template($renderer, 'test');

        $result = $template->setLayout('layouts/main');

        self::assertSame($template, $result);
        self::assertSame($layoutContent, $template->render());
    }

    /**
     * @throws Exception
     */
    public function testHasBlockReturnsFalseWhenNotSet(): void
    {
        $renderer = self::createStub(RendererContract::class);
        $template = new Template($renderer, 'test');

        self::assertFalse($template->hasBlock('content'));
    }

    /**
     * @throws Exception
     */
    public function testGetBlockReturnsEmptyStringWhenNotSet(): void
    {
        $renderer = self::createStub(RendererContract::class);
        $template = new Template($renderer, 'test');

        self::assertSame('', $template->getBlock('content'));
    }

    /**
     * @throws Exception
     */
    public function testStartAndEndBlock(): void
    {
        $blockContent = 'Block content here';

        $renderer = $this->createMock(RendererContract::class);
        $renderer->expects($this->once())
            ->method('startRender');
        $renderer->expects($this->once())
            ->method('endRender')
            ->willReturn($blockContent);

        $template = new Template($renderer, 'test');

        $template->startBlock('content');
        $template->endBlock();

        self::assertTrue($template->hasBlock('content'));
        self::assertSame($blockContent, $template->getBlock('content'));
    }

    /**
     * @throws Exception
     */
    public function testNestedBlocks(): void
    {
        $outerContent = 'Outer block';
        $innerContent = 'Inner block';

        $renderer = $this->createMock(RendererContract::class);
        $renderer->expects($this->exactly(2))
            ->method('startRender');
        $renderer->expects($this->exactly(2))
            ->method('endRender')
            ->willReturnOnConsecutiveCalls($innerContent, $outerContent);

        $template = new Template($renderer, 'test');

        $template->startBlock('outer');
        $template->startBlock('inner');
        $template->endBlock();
        $template->endBlock();

        self::assertTrue($template->hasBlock('outer'));
        self::assertTrue($template->hasBlock('inner'));
        self::assertSame($outerContent, $template->getBlock('outer'));
        self::assertSame($innerContent, $template->getBlock('inner'));
    }

    /**
     * @throws Exception
     */
    public function testGetPartial(): void
    {
        $partialContent = '<div>Partial content</div>';

        $renderer = $this->createMock(RendererContract::class);
        $renderer->expects($this->once())
            ->method('renderFile')
            ->with(
                'partials/header',
                self::callback(static fn ($variables) => isset($variables['template']) && $variables['template'] instanceof Template)
            )
            ->willReturn($partialContent);

        $template = new Template($renderer, 'test');

        self::assertSame($partialContent, $template->getPartial('partials/header'));
    }

    /**
     * @throws Exception
     */
    public function testGetPartialWithVariables(): void
    {
        $partialContent = '<div>Welcome, John</div>';

        $renderer = $this->createMock(RendererContract::class);
        $renderer->expects($this->once())
            ->method('renderFile')
            ->with(
                'partials/greeting',
                self::callback(static fn ($variables) => $variables['name'] === 'John' && isset($variables['template']))
            )
            ->willReturn($partialContent);

        $template = new Template($renderer, 'test');

        self::assertSame($partialContent, $template->getPartial('partials/greeting', ['name' => 'John']));
    }

    /**
     * @throws Exception
     */
    public function testRender(): void
    {
        $renderedContent = '<html lang="en"><body>Hello</body></html>';

        $renderer = $this->createMock(RendererContract::class);
        $renderer->expects($this->once())
            ->method('renderFile')
            ->with(
                'home',
                self::callback(static fn ($variables) => isset($variables['template']) && $variables['template'] instanceof Template)
            )
            ->willReturn($renderedContent);

        $template = new Template($renderer, 'home');

        self::assertSame($renderedContent, $template->render());
    }

    /**
     * @throws Exception
     */
    public function testRenderWithVariables(): void
    {
        $renderedContent = '<html lang="en"><body>Hello, World</body></html>';

        $renderer = $this->createMock(RendererContract::class);
        $renderer->expects($this->once())
            ->method('renderFile')
            ->with(
                'greeting',
                self::callback(static fn ($variables) => $variables['name'] === 'World' && isset($variables['template']))
            )
            ->willReturn($renderedContent);

        $template = new Template($renderer, 'greeting');

        self::assertSame($renderedContent, $template->render(['name' => 'World']));
    }

    /**
     * @throws Exception
     */
    public function testRenderWithLayout(): void
    {
        $templateContent = '<main>Page content</main>';
        $layoutContent   = '<html lang="en"><body><main>Page content</main></body></html>';

        $renderer = $this->createMock(RendererContract::class);
        $renderer->expects($this->exactly(2))
            ->method('renderFile')
            ->willReturnOnConsecutiveCalls($templateContent, $layoutContent);

        $template = new Template($renderer, 'page');
        $template->setLayout('layouts/main');

        self::assertSame($layoutContent, $template->render());
    }

    /**
     * @throws Exception
     */
    public function testToString(): void
    {
        $renderedContent = '<html lang="en"><body>ToString</body></html>';

        $renderer = $this->createMock(RendererContract::class);
        $renderer->expects($this->once())
            ->method('renderFile')
            ->willReturn($renderedContent);

        $template = new Template($renderer, 'test');

        self::assertSame($renderedContent, (string) $template);
    }

    /**
     * @throws Exception
     */
    public function testRenderWithRecursiveLayoutChange(): void
    {
        $templateContent    = '<main>Page content</main>';
        $firstLayoutContent = '<div>First layout</div>';
        $finalLayoutContent = '<html lang="en"><body>Final layout</body></html>';

        $template = null;

        $renderer = $this->createMock(RendererContract::class);
        $renderer->expects($this->exactly(3))
            ->method('renderFile')
            ->willReturnCallback(
                static function (string $path) use (
                    &$template,
                    $templateContent,
                    $firstLayoutContent,
                    $finalLayoutContent
                ): string {
                    if ($path === 'page') {
                        return $templateContent;
                    }

                    if ($path === 'layouts/first') {
                        $template->setLayout('layouts/final');

                        return $firstLayoutContent;
                    }

                    return $finalLayoutContent;
                }
            );

        $template = new Template($renderer, 'page');
        $template->setLayout('layouts/first');

        self::assertSame($finalLayoutContent, $template->render());
    }

    /**
     * @throws Exception
     */
    public function testEscapeThrowsExceptionOnEncodingFailure(): void
    {
        $renderer = self::createStub(RendererContract::class);
        $template = new class($renderer, 'test') extends Template {
            protected function convertEncoding(string $value): string|false
            {
                return false;
            }
        };

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Error occurred when encoding `test`');

        $template->escape('test');
    }
}
