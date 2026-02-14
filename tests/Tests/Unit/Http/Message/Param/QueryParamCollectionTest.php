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

namespace Valkyrja\Tests\Unit\Http\Message\Param;

use InvalidArgumentException;
use stdClass;
use Valkyrja\Http\Message\Param\Contract\QueryParamCollectionContract;
use Valkyrja\Http\Message\Param\QueryParamCollection;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class QueryParamCollectionTest extends TestCase
{
    protected QueryParamCollection $paramData;

    protected function setUp(): void
    {
        $this->paramData = new QueryParamCollection(['page' => '1', 'sort' => 'name']);
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(QueryParamCollectionContract::class, $this->paramData);
    }

    public function testConstructorWithNoParams(): void
    {
        $paramData = new QueryParamCollection();

        self::assertEmpty($paramData->getAll());
    }

    public function testConstructorWithStringParams(): void
    {
        self::assertSame('1', $this->paramData->get('page'));
        self::assertSame('name', $this->paramData->get('sort'));
    }

    public function testConstructorWithNestedParamData(): void
    {
        $nested    = new QueryParamCollection(['min' => '10', 'max' => '100']);
        $paramData = new QueryParamCollection(['filter' => $nested]);

        $params = $paramData->getAll();

        self::assertInstanceOf(QueryParamCollection::class, $params['filter']);
    }

    public function testGetParamReturnsString(): void
    {
        $result = $this->paramData->get('page');

        self::assertIsString($result);
        self::assertSame('1', $result);
    }

    public function testGetParamReturnsNullForMissing(): void
    {
        self::assertNull($this->paramData->get('nonexistent'));
    }

    public function testHasParam(): void
    {
        self::assertTrue($this->paramData->has('page'));
        self::assertTrue($this->paramData->has('sort'));
        self::assertFalse($this->paramData->has('nonexistent'));
    }

    public function testGetParams(): void
    {
        $params = $this->paramData->getAll();

        self::assertCount(2, $params);
        self::assertSame('1', $params['page']);
        self::assertSame('name', $params['sort']);
    }

    public function testOnlyParams(): void
    {
        $only = $this->paramData->getOnly('page');

        self::assertCount(1, $only);
        self::assertSame('1', $only['page']);
        self::assertArrayNotHasKey('sort', $only);
    }

    public function testExceptParams(): void
    {
        $except = $this->paramData->getAllExcept('page');

        self::assertCount(1, $except);
        self::assertSame('name', $except['sort']);
        self::assertArrayNotHasKey('page', $except);
    }

    public function testWithParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->with(['search' => 'test']);

        self::assertNotSame($this->paramData, $new);
        self::assertSame('test', $new->get('search'));
        self::assertNull($new->get('page'));
    }

    public function testWithParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->with(['search' => 'test']);

        self::assertSame('1', $this->paramData->get('page'));
    }

    public function testWithAddedParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->withAdded(['limit' => '25']);

        self::assertNotSame($this->paramData, $new);
        self::assertSame('1', $new->get('page'));
        self::assertSame('name', $new->get('sort'));
        self::assertSame('25', $new->get('limit'));
    }

    public function testWithAddedParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->withAdded(['limit' => '25']);

        self::assertFalse($this->paramData->has('limit'));
    }

    public function testWithParamsThrowsForInvalidParam(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->paramData->with(['invalid' => new stdClass()]);
    }

    public function testFromArray(): void
    {
        $paramData = QueryParamCollection::fromArray(['q' => 'search', 'page' => '2']);

        self::assertSame('search', $paramData->get('q'));
        self::assertSame('2', $paramData->get('page'));
    }

    public function testFromArrayWithNestedArray(): void
    {
        $paramData = QueryParamCollection::fromArray(['filter' => ['min' => '10']]);

        $params = $paramData->getAll();

        self::assertInstanceOf(QueryParamCollection::class, $params['filter']);
    }
}
