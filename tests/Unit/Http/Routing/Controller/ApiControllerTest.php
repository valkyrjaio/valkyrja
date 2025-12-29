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

namespace Valkyrja\Tests\Unit\Http\Routing\Controller;

use JsonException;
use Override;
use Valkyrja\Api\Constant\Status;
use Valkyrja\Api\Manager\Api;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Factory\ResponseFactory;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Throwable\Exception\HttpException;
use Valkyrja\Tests\Classes\Http\Routing\Controller\ApiControllerClass;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Throwable\Handler\ThrowableHandler;

use const JSON_THROW_ON_ERROR;

/**
 * Test the Controller service.
 *
 * @author Melech Mizrachi
 */
class ApiControllerTest extends TestCase
{
    protected ApiControllerClass $controller;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $request         = new ServerRequest();
        $responseFactory = new ResponseFactory();
        $api             = new Api($responseFactory);

        $this->controller = new ApiControllerClass(
            api: $api,
            request: $request,
            responseFactory: $responseFactory,
        );
    }

    /**
     * @throws JsonException
     */
    public function testApiJsonResponseDefaults(): void
    {
        $data = [
            'some' => 'data',
        ];

        $apiJsonResponse = $this->controller->createApiJsonResponse(data: $data);
        $apiJsonResponse->getBody()->rewind();
        $body = $apiJsonResponse->getBody()->getContents();

        self::assertStringContainsString(json_encode($data, JSON_THROW_ON_ERROR), $body);
        self::assertStringContainsString(Status::SUCCESS, $body);
        self::assertStringContainsString((string) StatusCode::OK->value, $body);
    }

    /**
     * @throws JsonException
     */
    public function testApiJsonResponse(): void
    {
        $data     = [
            'some' => 'data',
        ];
        $message  = 'a message';
        $errors   = ['error'];
        $warnings = ['warning'];

        $apiJsonResponse = $this->controller->createApiJsonResponse(
            data: $data,
            message: $message,
            status: Status::FAIL,
            statusCode: StatusCode::I_AM_A_TEAPOT,
            errors: $errors,
            warnings: $warnings,
        );
        $apiJsonResponse->getBody()->rewind();
        $body = $apiJsonResponse->getBody()->getContents();

        self::assertStringContainsString(json_encode($data, JSON_THROW_ON_ERROR), $body);
        self::assertStringContainsString(json_encode($errors, JSON_THROW_ON_ERROR), $body);
        self::assertStringContainsString(json_encode($warnings, JSON_THROW_ON_ERROR), $body);
        self::assertStringContainsString($message, $body);
        self::assertStringContainsString(Status::FAIL, $body);
        self::assertStringContainsString((string) StatusCode::I_AM_A_TEAPOT->value, $body);
    }

    /**
     * @throws JsonException
     */
    public function testGetExceptionResponseDefaults(): void
    {
        $exception = new HttpException(message: 'this is a message');

        $jsonFromException = $this->controller->getExceptionResponse(exception: $exception);
        $jsonFromException->getBody()->rewind();
        $body = $jsonFromException->getBody()->getContents();

        $json = [
            'traceCode' => ThrowableHandler::getTraceCode($exception),
        ];

        self::assertStringContainsString(json_encode($json, JSON_THROW_ON_ERROR), $body);
        self::assertStringContainsString($exception->getMessage(), $body);
        self::assertStringContainsString(Status::ERROR, $body);
        self::assertStringContainsString((string) StatusCode::INTERNAL_SERVER_ERROR->value, $body);
    }

    /**
     * @throws JsonException
     */
    public function testGetExceptionResponse(): void
    {
        $exception = new HttpException(message: 'this is a message');
        $message   = 'a message';
        $errors    = ['error'];
        $warnings  = ['warning'];

        $jsonFromException = $this->controller->getExceptionResponse(
            exception: $exception,
            message: $message,
            status: Status::FAIL,
            statusCode: StatusCode::I_AM_A_TEAPOT,
            errors: $errors,
            warnings: $warnings
        );
        $jsonFromException->getBody()->rewind();
        $body = $jsonFromException->getBody()->getContents();

        $json = [
            'traceCode' => ThrowableHandler::getTraceCode($exception),
        ];

        self::assertStringContainsString(json_encode($json, JSON_THROW_ON_ERROR), $body);
        self::assertStringContainsString(json_encode($errors, JSON_THROW_ON_ERROR), $body);
        self::assertStringContainsString(json_encode($warnings, JSON_THROW_ON_ERROR), $body);
        self::assertStringNotContainsString($exception->getMessage(), $body);
        self::assertStringContainsString($message, $body);
        self::assertStringContainsString(Status::FAIL, $body);
        self::assertStringContainsString((string) StatusCode::I_AM_A_TEAPOT->value, $body);
    }
}
