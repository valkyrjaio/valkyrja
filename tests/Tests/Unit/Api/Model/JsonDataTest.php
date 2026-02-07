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

use stdClass;
use Valkyrja\Api\Model\Contract\JsonDataContract;
use Valkyrja\Api\Model\JsonData;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the JsonData data model.
 */
final class JsonDataTest extends TestCase
{
    protected JsonData $jsonData;

    protected function setUp(): void
    {
        $this->jsonData = new JsonData();
    }

    public function testImplementsContract(): void
    {
        self::assertInstanceOf(JsonDataContract::class, $this->jsonData);
    }

    public function testDefaultValues(): void
    {
        self::assertNull($this->jsonData->getItem());
        self::assertSame('item', $this->jsonData->getItemKey());
        self::assertNull($this->jsonData->getItems());
        self::assertSame('items', $this->jsonData->getItemsKey());
        self::assertNull($this->jsonData->getTotal());
        self::assertNull($this->jsonData->getMessages());
        self::assertNull($this->jsonData->getData());
    }

    public function testGetAndSetItem(): void
    {
        $item       = new stdClass();
        $item->name = 'Test';

        $result = $this->jsonData->setItem($item);

        self::assertSame($this->jsonData, $result);
        self::assertSame($item, $this->jsonData->getItem());
    }

    public function testSetItemWithNull(): void
    {
        $this->jsonData->setItem(new stdClass());
        $this->jsonData->setItem(null);

        self::assertNull($this->jsonData->getItem());
    }

    public function testGetAndSetItemKey(): void
    {
        $result = $this->jsonData->setItemKey('user');

        self::assertSame($this->jsonData, $result);
        self::assertSame('user', $this->jsonData->getItemKey());
    }

    public function testGetAndSetItems(): void
    {
        $items = [new stdClass(), new stdClass()];

        $result = $this->jsonData->setItems($items);

        self::assertSame($this->jsonData, $result);
        self::assertSame($items, $this->jsonData->getItems());
    }

    public function testSetItemsWithNull(): void
    {
        $this->jsonData->setItems([new stdClass()]);
        $this->jsonData->setItems(null);

        self::assertNull($this->jsonData->getItems());
    }

    public function testGetAndSetItemsKey(): void
    {
        $result = $this->jsonData->setItemsKey('users');

        self::assertSame($this->jsonData, $result);
        self::assertSame('users', $this->jsonData->getItemsKey());
    }

    public function testGetAndSetTotal(): void
    {
        $result = $this->jsonData->setTotal(100);

        self::assertSame($this->jsonData, $result);
        self::assertSame(100, $this->jsonData->getTotal());
    }

    public function testSetTotalWithNull(): void
    {
        $this->jsonData->setTotal(50);
        $this->jsonData->setTotal(null);

        self::assertNull($this->jsonData->getTotal());
    }

    public function testGetAndSetMessages(): void
    {
        $messages = ['Message 1', 'Message 2'];

        $result = $this->jsonData->setMessages($messages);

        self::assertSame($this->jsonData, $result);
        self::assertSame($messages, $this->jsonData->getMessages());
    }

    public function testSetMessagesWithNull(): void
    {
        $this->jsonData->setMessages(['Message']);
        $this->jsonData->setMessages(null);

        self::assertNull($this->jsonData->getMessages());
    }

    public function testGetAndSetData(): void
    {
        $data = ['key' => 'value', 'number' => 42];

        $result = $this->jsonData->setData($data);

        self::assertSame($this->jsonData, $result);
        self::assertSame($data, $this->jsonData->getData());
    }

    public function testSetDataWithNull(): void
    {
        $this->jsonData->setData(['key' => 'value']);
        $this->jsonData->setData(null);

        self::assertNull($this->jsonData->getData());
    }

    public function testAsArrayWithEmptyJsonData(): void
    {
        $result = $this->jsonData->asArray();

        self::assertSame([], $result);
    }

    public function testAsArrayWithDataOnly(): void
    {
        $data = ['key' => 'value'];
        $this->jsonData->setData($data);

        $result = $this->jsonData->asArray();

        self::assertSame($data, $result);
    }

    public function testAsArrayWithMessages(): void
    {
        $messages = ['Message 1', 'Message 2'];
        $this->jsonData->setMessages($messages);

        $result = $this->jsonData->asArray();

        self::assertSame(['messages' => $messages], $result);
    }

    public function testAsArrayExcludesEmptyMessages(): void
    {
        $this->jsonData->setMessages([]);

        $result = $this->jsonData->asArray();

        self::assertArrayNotHasKey('messages', $result);
    }

    public function testAsArrayWithItem(): void
    {
        $item       = new stdClass();
        $item->name = 'Test';
        $this->jsonData->setItem($item);
        $this->jsonData->setItemKey('user');

        $result = $this->jsonData->asArray();

        self::assertArrayHasKey('user', $result);
        self::assertSame($item, $result['user']);
    }

    public function testAsArrayWithItems(): void
    {
        $items = [new stdClass(), new stdClass()];
        $this->jsonData->setItems($items);
        $this->jsonData->setItemsKey('users');

        $result = $this->jsonData->asArray();

        self::assertArrayHasKey('users', $result);
        self::assertSame($items, $result['users']);
    }

    public function testAsArrayExcludesEmptyItems(): void
    {
        $this->jsonData->setItems([]);

        $result = $this->jsonData->asArray();

        self::assertArrayNotHasKey('items', $result);
    }

    public function testAsArrayWithTotal(): void
    {
        $this->jsonData->setTotal(50);

        $result = $this->jsonData->asArray();

        self::assertArrayHasKey('total', $result);
        self::assertSame(50, $result['total']);
    }

    public function testAsArrayWithAllProperties(): void
    {
        $item     = new stdClass();
        $item->id = 1;
        $items    = [new stdClass(), new stdClass()];
        $messages = ['Info message'];
        $data     = ['extra' => 'data'];

        $this->jsonData
            ->setData($data)
            ->setMessages($messages)
            ->setItem($item)
            ->setItemKey('user')
            ->setItems($items)
            ->setItemsKey('users')
            ->setTotal(100);

        $result = $this->jsonData->asArray();

        self::assertSame('data', $result['extra']);
        self::assertSame($messages, $result['messages']);
        self::assertSame($item, $result['user']);
        self::assertSame($items, $result['users']);
        self::assertSame(100, $result['total']);
    }

    public function testAsArrayDataMergesWithOtherFields(): void
    {
        $this->jsonData->setData(['existing' => 'value']);
        $this->jsonData->setTotal(10);

        $result = $this->jsonData->asArray();

        self::assertSame('value', $result['existing']);
        self::assertSame(10, $result['total']);
    }

    public function testFluentInterface(): void
    {
        $item  = new stdClass();
        $items = [new stdClass()];

        $result = $this->jsonData
            ->setItem($item)
            ->setItemKey('user')
            ->setItems($items)
            ->setItemsKey('users')
            ->setTotal(1)
            ->setMessages(['Message'])
            ->setData(['key' => 'value']);

        self::assertSame($this->jsonData, $result);
    }
}
