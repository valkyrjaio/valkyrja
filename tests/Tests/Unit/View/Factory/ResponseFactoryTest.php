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

namespace Valkyrja\Tests\Unit\View\Factory;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract as HttpMessageResponseFactoryContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Factory\Contract\ResponseFactoryContract;
use Valkyrja\View\Factory\ResponseFactory;
use Valkyrja\View\Renderer\Contract\RendererContract;
use Valkyrja\View\Template\Contract\TemplateContract;

/**
 * Test the ResponseFactory class.
 */
final class ResponseFactoryTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testImplementsContract(): void
    {
        $factory = new ResponseFactory();

        self::assertInstanceOf(ResponseFactoryContract::class, $factory);
    }

    /**
     * @throws Exception
     */
    public function testCreateResponseFromViewWithDefaults(): void
    {
        $templateContent = '<html lang="en"><body>Test</body></html>';
        $templateName    = 'test-template';

        $template = $this->createMock(TemplateContract::class);
        $template->expects($this->once())
            ->method('render')
            ->willReturn($templateContent);

        $renderer = $this->createMock(RendererContract::class);
        $renderer->expects($this->once())
            ->method('createTemplate')
            ->with($templateName, [])
            ->willReturn($template);

        $response = self::createStub(ResponseContract::class);

        $httpResponseFactory = $this->createMock(HttpMessageResponseFactoryContract::class);
        $httpResponseFactory->expects($this->once())
            ->method('createResponse')
            ->with($templateContent, null, null)
            ->willReturn($response);

        $factory = new ResponseFactory($httpResponseFactory, $renderer);
        $result  = $factory->createResponseFromView($templateName);

        self::assertSame($response, $result);
    }

    /**
     * @throws Exception
     */
    public function testCreateResponseFromViewWithData(): void
    {
        $templateContent = '<html lang="en"><body>Hello, World!</body></html>';
        $templateName    = 'greeting';
        $data            = ['name' => 'World'];

        $template = $this->createMock(TemplateContract::class);
        $template->expects($this->once())
            ->method('render')
            ->willReturn($templateContent);

        $renderer = $this->createMock(RendererContract::class);
        $renderer->expects($this->once())
            ->method('createTemplate')
            ->with($templateName, $data)
            ->willReturn($template);

        $response = self::createStub(ResponseContract::class);

        $httpResponseFactory = $this->createMock(HttpMessageResponseFactoryContract::class);
        $httpResponseFactory->expects($this->once())
            ->method('createResponse')
            ->with($templateContent, null, null)
            ->willReturn($response);

        $factory = new ResponseFactory($httpResponseFactory, $renderer);
        $result  = $factory->createResponseFromView($templateName, $data);

        self::assertSame($response, $result);
    }

    /**
     * @throws Exception
     */
    public function testCreateResponseFromViewWithStatusCode(): void
    {
        $templateContent = '<html lang="en"><body>Not Found</body></html>';
        $templateName    = 'errors/404';
        $statusCode      = StatusCode::NOT_FOUND;

        $template = $this->createMock(TemplateContract::class);
        $template->expects($this->once())
            ->method('render')
            ->willReturn($templateContent);

        $renderer = $this->createMock(RendererContract::class);
        $renderer->expects($this->once())
            ->method('createTemplate')
            ->with($templateName, [])
            ->willReturn($template);

        $response = self::createStub(ResponseContract::class);

        $httpResponseFactory = $this->createMock(HttpMessageResponseFactoryContract::class);
        $httpResponseFactory->expects($this->once())
            ->method('createResponse')
            ->with($templateContent, $statusCode, null)
            ->willReturn($response);

        $factory = new ResponseFactory($httpResponseFactory, $renderer);
        $result  = $factory->createResponseFromView($templateName, null, $statusCode);

        self::assertSame($response, $result);
    }

    /**
     * @throws Exception
     */
    public function testCreateResponseFromViewWithHeaders(): void
    {
        $templateContent = '<html lang="en"><body>Test</body></html>';
        $templateName    = 'test-template';
        $headers         = HeaderCollection::fromArray([new Header('X-Custom-Header', 'value1', 'value2')]);

        $template = $this->createMock(TemplateContract::class);
        $template->expects($this->once())
            ->method('render')
            ->willReturn($templateContent);

        $renderer = $this->createMock(RendererContract::class);
        $renderer->expects($this->once())
            ->method('createTemplate')
            ->with($templateName, [])
            ->willReturn($template);

        $response = self::createStub(ResponseContract::class);

        $httpResponseFactory = $this->createMock(HttpMessageResponseFactoryContract::class);
        $httpResponseFactory->expects($this->once())
            ->method('createResponse')
            ->with($templateContent, null, $headers)
            ->willReturn($response);

        $factory = new ResponseFactory($httpResponseFactory, $renderer);
        $result  = $factory->createResponseFromView($templateName, null, null, $headers);

        self::assertSame($response, $result);
    }

    /**
     * @throws Exception
     */
    public function testCreateResponseFromViewWithAllParameters(): void
    {
        $templateContent = '<html lang="en"><body>Server Error</body></html>';
        $templateName    = 'errors/500';
        $data            = ['error' => 'Something went wrong'];
        $statusCode      = StatusCode::INTERNAL_SERVER_ERROR;
        $headers         = HeaderCollection::fromArray([new Header('X-Error-Id', 'abc123')]);

        $template = $this->createMock(TemplateContract::class);
        $template->expects($this->once())
            ->method('render')
            ->willReturn($templateContent);

        $renderer = $this->createMock(RendererContract::class);
        $renderer->expects($this->once())
            ->method('createTemplate')
            ->with($templateName, $data)
            ->willReturn($template);

        $response = self::createStub(ResponseContract::class);

        $httpResponseFactory = $this->createMock(HttpMessageResponseFactoryContract::class);
        $httpResponseFactory->expects($this->once())
            ->method('createResponse')
            ->with($templateContent, $statusCode, $headers)
            ->willReturn($response);

        $factory = new ResponseFactory($httpResponseFactory, $renderer);
        $result  = $factory->createResponseFromView($templateName, $data, $statusCode, $headers);

        self::assertSame($response, $result);
    }
}
