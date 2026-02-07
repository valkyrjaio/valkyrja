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

namespace Valkyrja\Tests\Unit\Http\Routing\Data;

use Valkyrja\Http\Routing\Constant\Regex;
use Valkyrja\Http\Routing\Data\Parameter;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Data\Cast;
use Valkyrja\Type\Enum\CastType;

/**
 * Test the Parameter service.
 */
class ParameterTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $name  = 'name';
        $regex = Regex::ALPHA;

        $parameter = new Parameter(name: $name, regex: $regex);

        self::assertSame($name, $parameter->getName());
        self::assertSame($regex, $parameter->getRegex());
        self::assertNull($parameter->getCast());
        self::assertFalse($parameter->isOptional());
        self::assertTrue($parameter->shouldCapture());
        self::assertNull($parameter->getDefault());
    }

    public function testConstructor(): void
    {
        $name          = 'name';
        $regex         = 'regex';
        $cast          = new Cast(CastType::false);
        $isOptional    = true;
        $shouldCapture = false;
        $default       = 'default';
        $value         = 'value';

        $parameter = new Parameter(...[
            'name'          => $name,
            'regex'         => $regex,
            'cast'          => $cast,
            'isOptional'    => $isOptional,
            'shouldCapture' => $shouldCapture,
            'default'       => $default,
            'value'         => $value,
        ]);

        self::assertSame($name, $parameter->getName());
        self::assertSame($regex, $parameter->getRegex());
        self::assertSame($cast, $parameter->getCast());
        self::assertSame($isOptional, $parameter->isOptional());
        self::assertSame($shouldCapture, $parameter->shouldCapture());
        self::assertSame($default, $parameter->getDefault());
        self::assertSame($value, $parameter->getValue());
    }

    public function testName(): void
    {
        $name  = 'name';
        $name2 = 'name2';
        $regex = Regex::ALPHA;

        $parameter  = new Parameter(name: $name, regex: $regex);
        $parameter2 = $parameter->withName($name2);

        self::assertNotSame($parameter, $parameter2);
        self::assertSame($name, $parameter->getName());
        self::assertSame($name2, $parameter2->getName());
    }

    public function testRegex(): void
    {
        $name   = 'name';
        $regex  = Regex::ALPHA;
        $regex2 = Regex::ALPHA_NUM;

        $parameter  = new Parameter(name: $name, regex: $regex);
        $parameter2 = $parameter->withRegex($regex2);

        self::assertNotSame($parameter, $parameter2);
        self::assertSame($regex, $parameter->getRegex());
        self::assertSame($regex2, $parameter2->getRegex());
    }

    public function testCast(): void
    {
        $name  = 'name';
        $regex = Regex::ALPHA;
        $cast  = new Cast(CastType::false);
        $cast2 = new Cast(CastType::true);

        $parameter  = new Parameter(name: $name, regex: $regex, cast: $cast);
        $parameter2 = $parameter->withCast($cast2);

        self::assertNotSame($parameter, $parameter2);
        self::assertSame($cast, $parameter->getCast());
        self::assertSame($cast2, $parameter2->getCast());
    }

    public function testOptional(): void
    {
        $name  = 'name';
        $regex = Regex::ALPHA;

        $parameter  = new Parameter(name: $name, regex: $regex);
        $parameter2 = $parameter->withIsOptional(true);

        self::assertNotSame($parameter, $parameter2);
        self::assertFalse($parameter->isOptional());
        self::assertTrue($parameter2->isOptional());
    }

    public function testShouldCapture(): void
    {
        $name  = 'name';
        $regex = Regex::ALPHA;

        $parameter  = new Parameter(name: $name, regex: $regex);
        $parameter2 = $parameter->withShouldCapture(false);

        self::assertNotSame($parameter, $parameter2);
        self::assertTrue($parameter->shouldCapture());
        self::assertFalse($parameter2->shouldCapture());
    }

    public function testDefault(): void
    {
        $name  = 'name';
        $regex = Regex::ALPHA;

        $default = 'test';

        $parameter  = new Parameter(name: $name, regex: $regex);
        $parameter2 = $parameter->withDefault($default);

        self::assertNotSame($parameter, $parameter2);
        self::assertNull($parameter->getDefault());
        self::assertSame($default, $parameter2->getDefault());
    }

    public function testValue(): void
    {
        $name  = 'name';
        $regex = Regex::ALPHA;

        $value = 'test';

        $parameter  = new Parameter(name: $name, regex: $regex);
        $parameter2 = $parameter->withValue($value);

        self::assertNotSame($parameter, $parameter2);
        self::assertNull($parameter->getValue());
        self::assertSame($value, $parameter2->getValue());
    }
}
