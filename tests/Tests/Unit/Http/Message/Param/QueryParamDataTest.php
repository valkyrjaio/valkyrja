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
use Valkyrja\Http\Message\Param\Contract\QueryParamDataContract;
use Valkyrja\Http\Message\Param\QueryParamData;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class QueryParamDataTest extends TestCase
{
    protected QueryParamData $paramData;

    protected function setUp(): void
    {
        $this->paramData = new QueryParamData(page: '1', sort: 'name');
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(QueryParamDataContract::class, $this->paramData);
    }

    public function testConstructorWithNoParams(): void
    {
        $paramData = new QueryParamData();

        self::assertEmpty($paramData->getParams());
    }

    public function testConstructorWithStringParams(): void
    {
        self::assertSame('1', $this->paramData->getParam('page'));
        self::assertSame('name', $this->paramData->getParam('sort'));
    }

    public function testConstructorWithNestedParamData(): void
    {
        $nested    = new QueryParamData(min: '10', max: '100');
        $paramData = new QueryParamData(filter: $nested);

        $params = $paramData->getParams();

        self::assertInstanceOf(QueryParamData::class, $params['filter']);
    }

    public function testGetParamReturnsString(): void
    {
        $result = $this->paramData->getParam('page');

        self::assertIsString($result);
        self::assertSame('1', $result);
    }

    public function testGetParamReturnsNullForMissing(): void
    {
        self::assertNull($this->paramData->getParam('nonexistent'));
    }

    public function testHasParam(): void
    {
        self::assertTrue($this->paramData->hasParam('page'));
        self::assertTrue($this->paramData->hasParam('sort'));
        self::assertFalse($this->paramData->hasParam('nonexistent'));
    }

    public function testGetParams(): void
    {
        $params = $this->paramData->getParams();

        self::assertCount(2, $params);
        self::assertSame('1', $params['page']);
        self::assertSame('name', $params['sort']);
    }

    public function testOnlyParams(): void
    {
        $only = $this->paramData->onlyParams('page');

        self::assertCount(1, $only);
        self::assertSame('1', $only['page']);
        self::assertArrayNotHasKey('sort', $only);
    }

    public function testExceptParams(): void
    {
        $except = $this->paramData->exceptParams('page');

        self::assertCount(1, $except);
        self::assertSame('name', $except['sort']);
        self::assertArrayNotHasKey('page', $except);
    }

    public function testWithParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->withParams(['search' => 'test']);

        self::assertNotSame($this->paramData, $new);
        self::assertSame('test', $new->getParam('search'));
        self::assertNull($new->getParam('page'));
    }

    public function testWithParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->withParams(['search' => 'test']);

        self::assertSame('1', $this->paramData->getParam('page'));
    }

    public function testWithAddedParamsReturnsNewInstance(): void
    {
        $new = $this->paramData->withAddedParams(limit: '25');

        self::assertNotSame($this->paramData, $new);
        self::assertSame('1', $new->getParam('page'));
        self::assertSame('name', $new->getParam('sort'));
        self::assertSame('25', $new->getParam('limit'));
    }

    public function testWithAddedParamsDoesNotModifyOriginal(): void
    {
        $this->paramData->withAddedParams(limit: '25');

        self::assertFalse($this->paramData->hasParam('limit'));
    }

    public function testWithParamsThrowsForInvalidParam(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->paramData->withParams(['invalid' => new \stdClass()]);
    }

    public function testFromArray(): void
    {
        $paramData = $this->paramData->fromArray(['q' => 'search', 'page' => '2']);

        self::assertSame('search', $paramData->getParam('q'));
        self::assertSame('2', $paramData->getParam('page'));
    }

    public function testFromArrayWithNestedArray(): void
    {
        $paramData = $this->paramData->fromArray(['filter' => ['min' => '10']]);

        $params = $paramData->getParams();

        self::assertInstanceOf(QueryParamData::class, $params['filter']);
    }
}