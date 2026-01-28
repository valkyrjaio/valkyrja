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

namespace Valkyrja\Tests\Unit\Api\Manager;

use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use stdClass;
use Valkyrja\Api\Constant\Status;
use Valkyrja\Api\Manager\Api;
use Valkyrja\Api\Manager\Contract\ApiContract;
use Valkyrja\Api\Model\Contract\JsonContract;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Message\Response\Contract\JsonResponseContract;
use Valkyrja\Http\Message\Throwable\Exception\HttpException;
use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Api manager class.
 */
class ApiTest extends TestCase
{
    protected ResponseFactoryContract&MockObject $responseFactory;
    protected JsonResponseContract&MockObject $jsonResponse;
    protected Api $api;
    protected Api $apiDebug;

    protected function setUp(): void
    {
        $this->responseFactory = $this->createMock(ResponseFactoryContract::class);
        $this->jsonResponse    = $this->createMock(JsonResponseContract::class);

        $this->api      = new Api($this->responseFactory, false);
        $this->apiDebug = new Api($this->responseFactory, true);
    }

    public function testImplementsContract(): void
    {
        $this->responseFactory->expects($this->never())->method(self::anything());
        $this->jsonResponse->expects($this->never())->method(self::anything());

        self::assertInstanceOf(ApiContract::class, $this->api);
    }

    public function testJsonFromExceptionWithRegularException(): void
    {
        $this->responseFactory->expects($this->never())->method(self::anything());
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $exception = new Exception('Test error', 500);

        $result = $this->api->jsonFromException($exception);

        self::assertInstanceOf(JsonContract::class, $result);
        self::assertSame('Test error', $result->getMessage());
        self::assertSame(Status::ERROR, $result->getStatus());
        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $result->getStatusCode());

        $data = $result->getData();
        self::assertIsArray($data);
        self::assertSame(500, $data['code']);
        self::assertArrayNotHasKey('file', $data);
        self::assertArrayNotHasKey('line', $data);
        self::assertArrayNotHasKey('trace', $data);
    }

    public function testJsonFromExceptionWithDebugEnabled(): void
    {
        $this->responseFactory->expects($this->never())->method(self::anything());
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $exception = new Exception('Debug error', 123);

        $result = $this->apiDebug->jsonFromException($exception);

        $data = $result->getData();
        self::assertIsArray($data);
        self::assertSame(123, $data['code']);
        self::assertArrayHasKey('file', $data);
        self::assertArrayHasKey('line', $data);
        self::assertArrayHasKey('trace', $data);
        self::assertIsString($data['file']);
        self::assertIsInt($data['line']);
        self::assertIsArray($data['trace']);
    }

    public function testJsonFromExceptionWithHttpException(): void
    {
        $this->responseFactory->expects($this->never())->method(self::anything());
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $exception = new HttpException(StatusCode::NOT_FOUND, 'Resource not found');

        $result = $this->api->jsonFromException($exception);

        self::assertSame(StatusCode::NOT_FOUND, $result->getStatusCode());
        self::assertSame('Resource not found', $result->getMessage());
        self::assertSame(Status::ERROR, $result->getStatus());
    }

    public function testJsonResponseFromException(): void
    {
        $this->responseFactory->expects($this->once())
            ->method('createJsonResponse')
            ->willReturn($this->jsonResponse);
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $exception = new Exception('Error');

        $result = $this->api->jsonResponseFromException($exception);

        self::assertSame($this->jsonResponse, $result);
    }

    public function testJsonFromObject(): void
    {
        $this->responseFactory->expects($this->never())->method(self::anything());
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $object       = new stdClass();
        $object->name = 'Test';

        $result = $this->api->jsonFromObject($object);

        self::assertInstanceOf(JsonContract::class, $result);
        self::assertSame(Status::SUCCESS, $result->getStatus());
        self::assertSame(StatusCode::OK, $result->getStatusCode());

        $data = $result->getData();
        self::assertIsArray($data);
        self::assertArrayHasKey('stdclass', $data);
        self::assertSame($object, $data['stdclass']);
    }

    public function testJsonFromObjectSetsCorrectItemKey(): void
    {
        $this->responseFactory->expects($this->never())->method(self::anything());
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $object = new class {
            public string $value = 'test';
        };

        $result = $this->api->jsonFromObject($object);

        $data = $result->getData();
        self::assertIsArray($data);

        // The class name should be lowercased
        $keys = array_keys($data);
        self::assertCount(1, $keys);
    }

    public function testJsonResponseFromObject(): void
    {
        $this->responseFactory->expects($this->once())
            ->method('createJsonResponse')
            ->willReturn($this->jsonResponse);
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $object = new stdClass();

        $result = $this->api->jsonResponseFromObject($object);

        self::assertSame($this->jsonResponse, $result);
    }

    public function testJsonFromObjectsWithMultipleObjects(): void
    {
        $this->responseFactory->expects($this->never())->method(self::anything());
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $obj1     = new stdClass();
        $obj1->id = 1;
        $obj2     = new stdClass();
        $obj2->id = 2;

        $result = $this->api->jsonFromObjects($obj1, $obj2);

        self::assertInstanceOf(JsonContract::class, $result);

        $data = $result->getData();
        self::assertIsArray($data);
        self::assertArrayHasKey('stdclasss', $data);
        self::assertCount(2, $data['stdclasss']);
    }

    public function testJsonFromObjectsWithEmptyArray(): void
    {
        $this->responseFactory->expects($this->never())->method(self::anything());
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $result = $this->api->jsonFromObjects();

        self::assertInstanceOf(JsonContract::class, $result);

        $data = $result->getData();
        self::assertIsArray($data);
    }

    public function testJsonResponseFromObjects(): void
    {
        $this->responseFactory->expects($this->once())
            ->method('createJsonResponse')
            ->willReturn($this->jsonResponse);
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $obj1 = new stdClass();
        $obj2 = new stdClass();

        $result = $this->api->jsonResponseFromObjects($obj1, $obj2);

        self::assertSame($this->jsonResponse, $result);
    }

    public function testJsonFromArray(): void
    {
        $this->responseFactory->expects($this->never())->method(self::anything());
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $array = ['key' => 'value', 'number' => 42];

        $result = $this->api->jsonFromArray($array);

        self::assertInstanceOf(JsonContract::class, $result);
        self::assertSame(Status::SUCCESS, $result->getStatus());

        $data = $result->getData();
        self::assertIsArray($data);
        self::assertSame('value', $data['key']);
        self::assertSame(42, $data['number']);
    }

    public function testJsonFromArrayWithEmptyArray(): void
    {
        $this->responseFactory->expects($this->never())->method(self::anything());
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $result = $this->api->jsonFromArray([]);

        self::assertInstanceOf(JsonContract::class, $result);
        self::assertSame([], $result->getData());
    }

    public function testJsonFromArrayWithNestedArray(): void
    {
        $this->responseFactory->expects($this->never())->method(self::anything());
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $array = [
            'level1' => [
                'level2' => [
                    'value' => 'nested',
                ],
            ],
        ];

        $result = $this->api->jsonFromArray($array);

        $data = $result->getData();
        self::assertIsArray($data);
        self::assertSame('nested', $data['level1']['level2']['value']);
    }

    public function testJsonResponseFromArray(): void
    {
        $this->responseFactory->expects($this->once())
            ->method('createJsonResponse')
            ->willReturn($this->jsonResponse);
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $result = $this->api->jsonResponseFromArray(['key' => 'value']);

        self::assertSame($this->jsonResponse, $result);
    }

    public function testJsonFromEntity(): void
    {
        $this->responseFactory->expects($this->never())->method(self::anything());
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $entity = $this->createMock(EntityContract::class);
        $entity->expects($this->never())->method(self::anything());

        $result = $this->api->jsonFromEntity($entity);

        self::assertInstanceOf(JsonContract::class, $result);
    }

    public function testJsonResponseFromEntity(): void
    {
        $this->responseFactory->expects($this->once())
            ->method('createJsonResponse')
            ->willReturn($this->jsonResponse);
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $entity = $this->createMock(EntityContract::class);
        $entity->expects($this->never())->method(self::anything());

        $result = $this->api->jsonResponseFromEntity($entity);

        self::assertSame($this->jsonResponse, $result);
    }

    public function testJsonFromEntities(): void
    {
        $this->responseFactory->expects($this->never())->method(self::anything());
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $entity1 = $this->createMock(EntityContract::class);
        $entity1->expects($this->never())->method(self::anything());
        $entity2 = $this->createMock(EntityContract::class);
        $entity2->expects($this->never())->method(self::anything());

        $result = $this->api->jsonFromEntities($entity1, $entity2);

        self::assertInstanceOf(JsonContract::class, $result);
    }

    public function testJsonResponseFromEntities(): void
    {
        $this->responseFactory->expects($this->once())
            ->method('createJsonResponse')
            ->willReturn($this->jsonResponse);
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $entity1 = $this->createMock(EntityContract::class);
        $entity1->expects($this->never())->method(self::anything());
        $entity2 = $this->createMock(EntityContract::class);
        $entity2->expects($this->never())->method(self::anything());

        $result = $this->api->jsonResponseFromEntities($entity1, $entity2);

        self::assertSame($this->jsonResponse, $result);
    }

    public function testCreateJsonResponsePassesCorrectData(): void
    {
        $this->responseFactory->expects($this->once())
            ->method('createJsonResponse')
            ->with(self::callback(
                static fn (array $data): bool => isset($data['data'])
                    && isset($data['statusCode'], $data['status'])
            ))
            ->willReturn($this->jsonResponse);
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $this->api->jsonResponseFromArray(['test' => 'value']);
    }
}
