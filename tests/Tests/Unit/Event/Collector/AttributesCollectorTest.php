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

namespace Valkyrja\Tests\Unit\Event\Collector;

use ReflectionException;
use Valkyrja\Attribute\Collector\Collector;
use Valkyrja\Dispatch\Data\Contract\ClassDispatchContract;
use Valkyrja\Dispatch\Data\Contract\MethodDispatchContract;
use Valkyrja\Event\Collector\AttributeCollector;
use Valkyrja\Event\Data\Contract\ListenerContract;
use Valkyrja\Reflection\Reflector\Reflector;
use Valkyrja\Tests\Classes\Event\Attribute\Attributed2Class;
use Valkyrja\Tests\Classes\Event\Attribute\AttributedClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the attributes collector class.
 */
final class AttributesCollectorTest extends TestCase
{
    /**
     * The value to test with.
     *
     * @var class-string
     */
    public const string VALUE1 = AttributeCollector::class;

    /**
     * The value to test with.
     *
     * @var class-string
     */
    public const string VALUE2 = TestCase::class;

    /**
     * The class  to test with.
     */
    protected AttributeCollector $class;

    /**
     * Setup the test.
     */
    protected function setUp(): void
    {
        $this->class = new AttributeCollector(
            new Collector(),
            new Reflector()
        );
    }

    /**
     * @throws ReflectionException
     */
    public function testGetListeners(): void
    {
        $attributes = $this->class->getListeners(AttributedClass::class);

        self::assertCount(6, $attributes);

        foreach ($attributes as $attribute) {
            self::assertInstanceOf(ListenerContract::class, $attribute);
        }

        self::assertSame(AttributedClass::class, $attributes[0]->getDispatch()->getClass());
        self::assertInstanceOf(ClassDispatchContract::class, $attributes[0]->getDispatch());
        self::assertSame(AttributedClass::class, $attributes[1]->getDispatch()->getClass());
        self::assertInstanceOf(ClassDispatchContract::class, $attributes[1]->getDispatch());
        self::assertSame(self::VALUE1, $attributes[0]->getEventId());
        self::assertSame(self::VALUE2, $attributes[1]->getEventId());

        self::assertInstanceOf(MethodDispatchContract::class, $attributes[2]->getDispatch());
        self::assertInstanceOf(MethodDispatchContract::class, $attributes[3]->getDispatch());
        self::assertSame(AttributedClass::class, $attributes[2]->getDispatch()->getClass());
        self::assertSame('staticMethod', $attributes[2]->getDispatch()->getMethod());
        self::assertSame(AttributedClass::class, $attributes[3]->getDispatch()->getClass());
        self::assertSame('staticMethod', $attributes[3]->getDispatch()->getMethod());
        self::assertTrue($attributes[2]->getDispatch()->isStatic());
        self::assertTrue($attributes[3]->getDispatch()->isStatic());
        self::assertSame(self::VALUE1, $attributes[2]->getEventId());
        self::assertSame(self::VALUE2, $attributes[3]->getEventId());

        self::assertInstanceOf(MethodDispatchContract::class, $attributes[4]->getDispatch());
        self::assertInstanceOf(MethodDispatchContract::class, $attributes[5]->getDispatch());
        self::assertSame(AttributedClass::class, $attributes[4]->getDispatch()->getClass());
        self::assertSame('method', $attributes[4]->getDispatch()->getMethod());
        self::assertSame(AttributedClass::class, $attributes[5]->getDispatch()->getClass());
        self::assertSame('method', $attributes[5]->getDispatch()->getMethod());
        self::assertFalse($attributes[4]->getDispatch()->isStatic());
        self::assertFalse($attributes[5]->getDispatch()->isStatic());
        self::assertSame(self::VALUE1, $attributes[4]->getEventId());
        self::assertSame(self::VALUE2, $attributes[5]->getEventId());
    }

    /**
     * @throws ReflectionException
     */
    public function testGetListeners2(): void
    {
        $attributes = $this->class->getListeners(Attributed2Class::class);

        self::assertCount(6, $attributes);

        foreach ($attributes as $attribute) {
            self::assertInstanceOf(ListenerContract::class, $attribute);
        }

        self::assertSame(self::VALUE1, $attributes[0]->getEventId());
        self::assertInstanceOf(ClassDispatchContract::class, $attributes[0]->getDispatch());

        self::assertSame(self::VALUE2, $attributes[1]->getEventId());
        self::assertInstanceOf(ClassDispatchContract::class, $attributes[1]->getDispatch());

        self::assertSame(self::VALUE1, $attributes[2]->getEventId());
        self::assertSame(self::VALUE2, $attributes[3]->getEventId());
        self::assertSame(self::VALUE1, $attributes[4]->getEventId());
        self::assertSame(self::VALUE2, $attributes[5]->getEventId());
    }
}
