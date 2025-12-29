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
use Valkyrja\Attribute\Collector\Collector as AttributesAttributes;
use Valkyrja\Dispatcher\Data\Contract\ClassDispatch;
use Valkyrja\Event\Collector\AttributeCollector;
use Valkyrja\Event\Data\Contract\Listener;
use Valkyrja\Reflection\Reflector\Reflector;
use Valkyrja\Tests\Classes\Event\Attribute\Attributed2Class;
use Valkyrja\Tests\Classes\Event\Attribute\AttributedClass;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the attributes collector class.
 *
 * @author Melech Mizrachi
 */
class AttributesCollectorTest extends TestCase
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
     * The class to test with.
     */
    protected AttributeCollector $class;

    /**
     * Setup the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->class = new AttributeCollector(
            new AttributesAttributes(),
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
            self::assertInstanceOf(Listener::class, $attribute);
        }

        self::assertSame(self::VALUE1, $attributes[0]->getEventId());
        self::assertSame(self::VALUE2, $attributes[1]->getEventId());

        self::assertSame(self::VALUE1, $attributes[2]->getEventId());
        self::assertSame(self::VALUE2, $attributes[3]->getEventId());
    }

    /**
     * @throws ReflectionException
     */
    public function testGetListeners2(): void
    {
        $attributes = $this->class->getListeners(Attributed2Class::class);

        self::assertCount(6, $attributes);

        foreach ($attributes as $attribute) {
            self::assertInstanceOf(Listener::class, $attribute);
        }

        self::assertSame(self::VALUE1, $attributes[0]->getEventId());
        self::assertInstanceOf(ClassDispatch::class, $attributes[0]->getDispatch());

        self::assertSame(self::VALUE2, $attributes[1]->getEventId());
        self::assertInstanceOf(ClassDispatch::class, $attributes[1]->getDispatch());

        self::assertSame(self::VALUE1, $attributes[2]->getEventId());
        self::assertSame(self::VALUE2, $attributes[3]->getEventId());
        self::assertSame(self::VALUE1, $attributes[4]->getEventId());
        self::assertSame(self::VALUE2, $attributes[5]->getEventId());
    }
}
