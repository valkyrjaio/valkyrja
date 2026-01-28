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

namespace Valkyrja\Tests\Unit\Api\Model;

use Valkyrja\Api\Constant\Status;
use Valkyrja\Api\Model\Contract\JsonContract;
use Valkyrja\Api\Model\Json;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Json data model.
 */
class JsonTest extends TestCase
{
    protected Json $json;

    protected function setUp(): void
    {
        $this->json = new Json();
    }

    public function testImplementsContract(): void
    {
        self::assertInstanceOf(JsonContract::class, $this->json);
    }

    public function testDefaultValues(): void
    {
        self::assertNull($this->json->getMessage());
        self::assertNull($this->json->getData());
        self::assertSame([], $this->json->getErrors());
        self::assertSame([], $this->json->getWarnings());
        self::assertSame(StatusCode::OK, $this->json->getStatusCode());
        self::assertSame(Status::SUCCESS, $this->json->getStatus());
    }

    public function testGetAndSetMessage(): void
    {
        $message = 'Test message';

        $result = $this->json->setMessage($message);

        self::assertSame($this->json, $result);
        self::assertSame($message, $this->json->getMessage());
    }

    public function testSetMessageWithNull(): void
    {
        $this->json->setMessage('Initial message');
        $this->json->setMessage(null);

        self::assertNull($this->json->getMessage());
    }

    public function testGetAndSetData(): void
    {
        $data = ['key' => 'value', 'nested' => ['a' => 1]];

        $result = $this->json->setData($data);

        self::assertSame($this->json, $result);
        self::assertSame($data, $this->json->getData());
    }

    public function testSetDataWithNull(): void
    {
        $this->json->setData(['key' => 'value']);
        $this->json->setData(null);

        self::assertNull($this->json->getData());
    }

    public function testGetAndSetErrors(): void
    {
        $errors = ['Error 1', 'Error 2'];

        $result = $this->json->setErrors($errors);

        self::assertSame($this->json, $result);
        self::assertSame($errors, $this->json->getErrors());
    }

    public function testSetError(): void
    {
        $this->json->setError('First error');
        $this->json->setError('Second error');

        self::assertSame(['First error', 'Second error'], $this->json->getErrors());
    }

    public function testSetErrorReturnsSelf(): void
    {
        $result = $this->json->setError('Error');

        self::assertSame($this->json, $result);
    }

    public function testGetAndSetWarnings(): void
    {
        $warnings = ['Warning 1', 'Warning 2'];

        $result = $this->json->setWarnings($warnings);

        self::assertSame($this->json, $result);
        self::assertSame($warnings, $this->json->getWarnings());
    }

    public function testSetWarning(): void
    {
        $this->json->setWarning('First warning');
        $this->json->setWarning('Second warning');

        self::assertSame(['First warning', 'Second warning'], $this->json->getWarnings());
    }

    public function testSetWarningReturnsSelf(): void
    {
        $result = $this->json->setWarning('Warning');

        self::assertSame($this->json, $result);
    }

    public function testGetAndSetStatusCode(): void
    {
        $statusCode = StatusCode::NOT_FOUND;

        $result = $this->json->setStatusCode($statusCode);

        self::assertSame($this->json, $result);
        self::assertSame($statusCode, $this->json->getStatusCode());
    }

    public function testGetAndSetStatus(): void
    {
        $status = Status::ERROR;

        $result = $this->json->setStatus($status);

        self::assertSame($this->json, $result);
        self::assertSame($status, $this->json->getStatus());
    }

    public function testJsonSerializeWithDefaults(): void
    {
        $result = $this->json->jsonSerialize();

        self::assertSame([
            'data'       => null,
            'statusCode' => StatusCode::OK,
            'status'     => Status::SUCCESS,
        ], $result);
    }

    public function testJsonSerializeWithMessage(): void
    {
        $this->json->setMessage('Test message');

        $result = $this->json->jsonSerialize();

        self::assertArrayHasKey('message', $result);
        self::assertSame('Test message', $result['message']);
    }

    public function testJsonSerializeExcludesEmptyMessage(): void
    {
        $this->json->setMessage('');

        $result = $this->json->jsonSerialize();

        self::assertArrayNotHasKey('message', $result);
    }

    public function testJsonSerializeWithAllData(): void
    {
        $data = ['key' => 'value'];
        $this->json->setData($data);
        $this->json->setMessage('Test');
        $this->json->setStatusCode(StatusCode::CREATED);
        $this->json->setStatus(Status::SUCCESS);

        $result = $this->json->jsonSerialize();

        self::assertSame($data, $result['data']);
        self::assertSame('Test', $result['message']);
        self::assertSame(StatusCode::CREATED, $result['statusCode']);
        self::assertSame(Status::SUCCESS, $result['status']);
    }

    public function testFluentInterface(): void
    {
        $result = $this->json
            ->setMessage('Message')
            ->setData(['key' => 'value'])
            ->setStatus(Status::ERROR)
            ->setStatusCode(StatusCode::BAD_REQUEST)
            ->setError('Error 1')
            ->setWarning('Warning 1');

        self::assertSame($this->json, $result);
        self::assertSame('Message', $this->json->getMessage());
        self::assertSame(['key' => 'value'], $this->json->getData());
        self::assertSame(Status::ERROR, $this->json->getStatus());
        self::assertSame(StatusCode::BAD_REQUEST, $this->json->getStatusCode());
        self::assertSame(['Error 1'], $this->json->getErrors());
        self::assertSame(['Warning 1'], $this->json->getWarnings());
    }
}
