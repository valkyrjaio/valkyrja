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

namespace Valkyrja\Tests\Unit\Cli\Routing\Data;

use Valkyrja\Cli\Interaction\Argument\Argument;
use Valkyrja\Cli\Routing\Data\ArgumentParameter;
use Valkyrja\Cli\Routing\Enum\ArgumentMode;
use Valkyrja\Cli\Routing\Enum\ArgumentValueMode;
use Valkyrja\Cli\Routing\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\BuiltIn\IntT;
use Valkyrja\Type\Data\Cast;
use Valkyrja\Type\Enum\CastType;

/**
 * Test the ArgumentParameter class.
 */
class ArgumentParameterTest extends TestCase
{
    /** @var non-empty-string */
    protected const string NAME = 'name';
    /** @var non-empty-string */
    protected const string DESCRIPTION = 'Test description';

    public function testDefaults(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;

        $parameter = new ArgumentParameter(
            name: $name,
            description: $description
        );

        self::assertSame($name, $parameter->getName());
        self::assertSame($description, $parameter->getDescription());
        self::assertNull($parameter->getCast());
        self::assertSame(ArgumentMode::OPTIONAL, $parameter->getMode());
        self::assertSame(ArgumentValueMode::DEFAULT, $parameter->getValueMode());
        self::assertEmpty($parameter->getArguments());
        self::assertEmpty($parameter->getCastValues());
        self::assertNull($parameter->getFirstValue());
        self::assertTrue($parameter->areValuesValid());
    }

    public function testSetState(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $cast        = new Cast(type: CastType::bool);
        $mode        = ArgumentMode::REQUIRED;
        $valueMode   = ArgumentValueMode::ARRAY;
        $arguments   = [new Argument('test')];

        $parameter = ArgumentParameter::__set_state([
            'name'        => $name,
            'description' => $description,
            'cast'        => $cast,
            'mode'        => $mode,
            'valueMode'   => $valueMode,
            'arguments'   => $arguments,
        ]);

        self::assertSame($name, $parameter->getName());
        self::assertSame($description, $parameter->getDescription());
        self::assertSame($cast, $parameter->getCast());
        self::assertSame($mode, $parameter->getMode());
        self::assertSame($valueMode, $parameter->getValueMode());
        self::assertSame($arguments, $parameter->getArguments());
        self::assertNotEmpty($parameter->getCastValues());
        self::assertSame('test', $parameter->getFirstValue());
        self::assertTrue($parameter->areValuesValid());
    }

    public function testName(): void
    {
        $name        = self::NAME;
        $name2       = 'name2';
        $description = self::DESCRIPTION;

        $parameter  = new ArgumentParameter(
            name: $name,
            description: $description
        );
        $parameter2 = $parameter->withName($name2);

        self::assertNotSame($parameter, $parameter2);
        self::assertSame($name, $parameter->getName());
        self::assertSame($description, $parameter->getDescription());
        self::assertNull($parameter->getCast());
        self::assertSame(ArgumentMode::OPTIONAL, $parameter->getMode());
        self::assertSame(ArgumentValueMode::DEFAULT, $parameter->getValueMode());
        self::assertEmpty($parameter->getArguments());
        self::assertEmpty($parameter->getCastValues());
        self::assertNull($parameter->getFirstValue());
        self::assertTrue($parameter->areValuesValid());

        self::assertSame($name2, $parameter2->getName());
        self::assertSame($description, $parameter2->getDescription());
        self::assertNull($parameter2->getCast());
        self::assertSame(ArgumentMode::OPTIONAL, $parameter2->getMode());
        self::assertSame(ArgumentValueMode::DEFAULT, $parameter2->getValueMode());
        self::assertEmpty($parameter2->getArguments());
        self::assertEmpty($parameter2->getCastValues());
        self::assertNull($parameter2->getFirstValue());
        self::assertTrue($parameter2->areValuesValid());
    }

    public function testDescription(): void
    {
        $name         = self::NAME;
        $description  = self::DESCRIPTION;
        $description2 = 'description2';

        $parameter  = new ArgumentParameter(
            name: $name,
            description: $description
        );
        $parameter2 = $parameter->withDescription($description2);

        self::assertNotSame($parameter, $parameter2);
        self::assertSame($name, $parameter->getName());
        self::assertSame($description, $parameter->getDescription());
        self::assertNull($parameter->getCast());
        self::assertSame(ArgumentMode::OPTIONAL, $parameter->getMode());
        self::assertSame(ArgumentValueMode::DEFAULT, $parameter->getValueMode());
        self::assertEmpty($parameter->getArguments());
        self::assertEmpty($parameter->getCastValues());
        self::assertNull($parameter->getFirstValue());
        self::assertTrue($parameter->areValuesValid());

        self::assertSame($name, $parameter2->getName());
        self::assertSame($description2, $parameter2->getDescription());
        self::assertNull($parameter2->getCast());
        self::assertSame(ArgumentMode::OPTIONAL, $parameter2->getMode());
        self::assertSame(ArgumentValueMode::DEFAULT, $parameter2->getValueMode());
        self::assertEmpty($parameter2->getArguments());
        self::assertEmpty($parameter2->getCastValues());
        self::assertNull($parameter2->getFirstValue());
        self::assertTrue($parameter2->areValuesValid());
    }

    public function testCast(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $cast        = new Cast(type: CastType::bool);

        $parameter  = new ArgumentParameter(
            name: $name,
            description: $description,
        );
        $parameter2 = $parameter->withCast($cast);

        self::assertNotSame($parameter, $parameter2);
        self::assertSame($name, $parameter->getName());
        self::assertSame($description, $parameter->getDescription());
        self::assertNull($parameter->getCast());
        self::assertSame(ArgumentMode::OPTIONAL, $parameter->getMode());
        self::assertSame(ArgumentValueMode::DEFAULT, $parameter->getValueMode());
        self::assertEmpty($parameter->getArguments());
        self::assertEmpty($parameter->getCastValues());
        self::assertNull($parameter->getFirstValue());
        self::assertTrue($parameter->areValuesValid());

        self::assertSame($name, $parameter2->getName());
        self::assertSame($description, $parameter2->getDescription());
        self::assertSame($cast, $parameter2->getCast());
        self::assertSame(ArgumentMode::OPTIONAL, $parameter2->getMode());
        self::assertSame(ArgumentValueMode::DEFAULT, $parameter2->getValueMode());
        self::assertEmpty($parameter2->getArguments());
        self::assertEmpty($parameter2->getCastValues());
        self::assertNull($parameter2->getFirstValue());
        self::assertTrue($parameter2->areValuesValid());
    }

    public function testMode(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $mode        = ArgumentMode::REQUIRED;

        $parameter  = new ArgumentParameter(
            name: $name,
            description: $description
        );
        $parameter2 = $parameter->withMode($mode);

        self::assertNotSame($parameter, $parameter2);
        self::assertSame($name, $parameter->getName());
        self::assertSame($description, $parameter->getDescription());
        self::assertNull($parameter->getCast());
        self::assertSame(ArgumentMode::OPTIONAL, $parameter->getMode());
        self::assertSame(ArgumentValueMode::DEFAULT, $parameter->getValueMode());
        self::assertEmpty($parameter->getArguments());
        self::assertEmpty($parameter->getCastValues());
        self::assertNull($parameter->getFirstValue());
        self::assertTrue($parameter->areValuesValid());

        self::assertSame($name, $parameter2->getName());
        self::assertSame($description, $parameter2->getDescription());
        self::assertNull($parameter2->getCast());
        self::assertSame(ArgumentMode::REQUIRED, $parameter2->getMode());
        self::assertSame(ArgumentValueMode::DEFAULT, $parameter2->getValueMode());
        self::assertEmpty($parameter2->getArguments());
        self::assertEmpty($parameter2->getCastValues());
        self::assertNull($parameter2->getFirstValue());
        self::assertFalse($parameter2->areValuesValid());
    }

    public function testValueMode(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $mode        = ArgumentValueMode::ARRAY;

        $parameter  = new ArgumentParameter(
            name: $name,
            description: $description
        );
        $parameter2 = $parameter->withValueMode($mode);

        self::assertNotSame($parameter, $parameter2);
        self::assertSame($name, $parameter->getName());
        self::assertSame($description, $parameter->getDescription());
        self::assertNull($parameter->getCast());
        self::assertSame(ArgumentMode::OPTIONAL, $parameter->getMode());
        self::assertSame(ArgumentValueMode::DEFAULT, $parameter->getValueMode());
        self::assertEmpty($parameter->getArguments());
        self::assertEmpty($parameter->getCastValues());
        self::assertNull($parameter->getFirstValue());
        self::assertTrue($parameter->areValuesValid());

        self::assertSame($name, $parameter2->getName());
        self::assertSame($description, $parameter2->getDescription());
        self::assertNull($parameter2->getCast());
        self::assertSame(ArgumentMode::OPTIONAL, $parameter2->getMode());
        self::assertSame(ArgumentValueMode::ARRAY, $parameter2->getValueMode());
        self::assertEmpty($parameter2->getArguments());
        self::assertEmpty($parameter2->getCastValues());
        self::assertNull($parameter2->getFirstValue());
        self::assertTrue($parameter2->areValuesValid());
    }

    public function testArguments(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $argument    = new Argument('test');
        $argument2   = new Argument('test2');

        $parameter  = new ArgumentParameter(
            name: $name,
            description: $description
        );
        $parameter2 = $parameter->withArguments($argument);
        $parameter3 = $parameter->withAddedArguments($argument2);
        $parameter4 = $parameter2->withAddedArguments($argument2);

        self::assertNotSame($parameter, $parameter2);
        self::assertSame($name, $parameter->getName());
        self::assertSame($description, $parameter->getDescription());
        self::assertNull($parameter->getCast());
        self::assertSame(ArgumentMode::OPTIONAL, $parameter->getMode());
        self::assertSame(ArgumentValueMode::DEFAULT, $parameter->getValueMode());
        self::assertEmpty($parameter->getArguments());
        self::assertEmpty($parameter->getCastValues());
        self::assertNull($parameter->getFirstValue());
        self::assertTrue($parameter->areValuesValid());

        self::assertSame($name, $parameter2->getName());
        self::assertSame($description, $parameter2->getDescription());
        self::assertNull($parameter2->getCast());
        self::assertSame(ArgumentMode::OPTIONAL, $parameter2->getMode());
        self::assertSame(ArgumentValueMode::DEFAULT, $parameter2->getValueMode());
        self::assertNotEmpty($parameter2->getArguments());
        self::assertSame([$argument], $parameter2->getArguments());
        self::assertSame(['test'], $parameter2->getCastValues());
        self::assertSame('test', $parameter2->getFirstValue());
        self::assertTrue($parameter2->areValuesValid());

        self::assertSame($name, $parameter3->getName());
        self::assertSame($description, $parameter3->getDescription());
        self::assertNull($parameter3->getCast());
        self::assertSame(ArgumentMode::OPTIONAL, $parameter3->getMode());
        self::assertSame(ArgumentValueMode::DEFAULT, $parameter3->getValueMode());
        self::assertNotEmpty($parameter3->getArguments());
        self::assertSame([$argument2], $parameter3->getArguments());
        self::assertSame(['test2'], $parameter3->getCastValues());
        self::assertSame('test2', $parameter3->getFirstValue());
        self::assertTrue($parameter3->areValuesValid());

        self::assertSame($name, $parameter4->getName());
        self::assertSame($description, $parameter4->getDescription());
        self::assertNull($parameter4->getCast());
        self::assertSame(ArgumentMode::OPTIONAL, $parameter4->getMode());
        self::assertSame(ArgumentValueMode::DEFAULT, $parameter4->getValueMode());
        self::assertNotEmpty($parameter4->getArguments());
        self::assertSame([$argument, $argument2], $parameter4->getArguments());
        self::assertSame(['test', 'test2'], $parameter4->getCastValues());
        self::assertSame('test', $parameter4->getFirstValue());
        self::assertFalse($parameter4->areValuesValid());
    }

    public function testGetCastValue(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $cast        = new Cast(type: CastType::int);
        $cast2       = new Cast(type: CastType::int, convert: false);
        $argument    = new Argument('1');
        $argument2   = new Argument('2');

        $parameter  = new ArgumentParameter(
            name: $name,
            description: $description,
            cast: $cast,
        );
        $parameter2 = $parameter->withArguments($argument, $argument2);
        $parameter3 = $parameter2->withCast($cast2);

        self::assertEmpty($parameter->getCastValues());

        self::assertNotEmpty($parameter2->getCastValues());
        self::assertSame([1, 2], $parameter2->getCastValues());

        self::assertNotEmpty($parameter3->getCastValues());
        self::assertInstanceOf(IntT::class, $value1 = $parameter3->getCastValues()[0]);
        self::assertSame(1, $value1->asValue());
        self::assertInstanceOf(IntT::class, $value2 = $parameter3->getCastValues()[1]);
        self::assertSame(2, $value2->asValue());
    }

    public function testAreValuesValid(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $argument    = new Argument('1');
        $argument2   = new Argument('2');

        $parameter  = new ArgumentParameter(
            name: $name,
            description: $description,
        );
        $parameter2 = $parameter->withArguments($argument);
        $parameter3 = $parameter->withArguments($argument, $argument2);
        $parameter4 = $parameter3->withValueMode(ArgumentValueMode::ARRAY);
        $parameter5 = $parameter->withMode(ArgumentMode::REQUIRED);
        $parameter6 = $parameter3->withMode(ArgumentMode::REQUIRED);

        self::assertTrue($parameter->areValuesValid());
        self::assertTrue($parameter2->areValuesValid());
        self::assertFalse($parameter3->areValuesValid());
        self::assertTrue($parameter4->areValuesValid());
        self::assertFalse($parameter5->areValuesValid());
        self::assertFalse($parameter6->areValuesValid());
    }

    public function testValidateValuesException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $name        = self::NAME;
        $description = self::DESCRIPTION;

        $parameter = new ArgumentParameter(
            name: $name,
            description: $description,
            mode: ArgumentMode::REQUIRED,
        );

        $parameter->validateValues();
    }

    public function testValidateValues(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;

        $parameter = new ArgumentParameter(
            name: $name,
            description: $description,
        );

        self::assertSame($parameter, $parameter->validateValues());
    }
}
