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

namespace Valkyrja\Tests\Unit\Events\Attributes;

use ReflectionException;
use Valkyrja\Attribute\Managers\Attributes as AttributesAttributes;
use Valkyrja\Event\Attributes\Attributes;
use Valkyrja\Event\Listener;
use Valkyrja\Reflection\Reflectors\Reflector;
use Valkyrja\Tests\Unit\Events\Attributes\Classes\AttributedClass;
use Valkyrja\Tests\Unit\Events\Attributes\Classes\AttributedClass2;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the attributes class.
 *
 * @author Melech Mizrachi
 */
class AttributesTest extends TestCase
{
    /**
     * The value to test with.
     *
     * @var string
     */
    public const VALUE1 = self::class;

    /**
     * The value to test with.
     *
     * @var string
     */
    public const VALUE2 = TestCase::class;

    /**
     * The class to test with.
     */
    protected Attributes $class;

    /**
     * Setup the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->class = new Attributes(
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

        self::assertCount(4, $attributes);

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
        $attributes = $this->class->getListeners(AttributedClass2::class);

        self::assertCount(6, $attributes);

        foreach ($attributes as $attribute) {
            self::assertInstanceOf(Listener::class, $attribute);
        }

        self::assertSame(self::VALUE1, $attributes[0]->getEventId());
        self::assertSame('__construct', $attributes[0]->getMethod());

        self::assertSame(self::VALUE2, $attributes[1]->getEventId());
        self::assertSame('__construct', $attributes[1]->getMethod());

        self::assertSame(self::VALUE1, $attributes[2]->getEventId());
        self::assertSame(self::VALUE2, $attributes[3]->getEventId());
        self::assertSame(self::VALUE1, $attributes[4]->getEventId());
        self::assertSame(self::VALUE2, $attributes[5]->getEventId());
    }
}
