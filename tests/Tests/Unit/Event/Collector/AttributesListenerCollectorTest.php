<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Event\Collector;

use Override;
use Valkyrja\Attribute\Collector\Collector;
use Valkyrja\Event\Collector\AttributeListenerCollector;
use Valkyrja\Event\Data\Contract\ListenerContract;
use Valkyrja\Tests\Fixtures\Event\Attribute\Attributed2Fixture;
use Valkyrja\Tests\Fixtures\Event\Attribute\AttributedFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the attributes collector class.
 */
final class AttributesListenerCollectorTest extends TestCase
{
    /**
     * The value to test with.
     *
     * @var class-string
     */
    public const string VALUE1 = AttributeListenerCollector::class;

    /**
     * The value to test with.
     *
     * @var class-string
     */
    public const string VALUE2 = TestCase::class;

    /**
     * The class  to test with.
     */
    protected AttributeListenerCollector $class;

    /**
     * Setup the test.
     */
    #[Override]
    protected function setUp(): void
    {
        $this->class = new AttributeListenerCollector(
            new Collector()
        );
    }

    public function testGetListeners(): void
    {
        $attributes = $this->class->getListeners(AttributedFixture::class);

        self::assertCount(6, $attributes);

        foreach ($attributes as $attribute) {
            self::assertInstanceOf(ListenerContract::class, $attribute);
        }

        self::assertSame(self::VALUE1, $attributes[0]->getEventId());
        self::assertSame(self::VALUE2, $attributes[1]->getEventId());

        self::assertSame(self::VALUE1, $attributes[2]->getEventId());
        self::assertSame(self::VALUE2, $attributes[3]->getEventId());
        self::assertSame([AttributedFixture::class, 'handler'], $attributes[2]->getHandler());
        self::assertSame([AttributedFixture::class, 'handler'], $attributes[3]->getHandler());

        self::assertSame(self::VALUE1, $attributes[4]->getEventId());
        self::assertSame(self::VALUE2, $attributes[5]->getEventId());
        self::assertSame([AttributedFixture::class, 'handler2'], $attributes[4]->getHandler());
        self::assertSame([AttributedFixture::class, 'handler2'], $attributes[5]->getHandler());
    }

    public function testGetListeners2(): void
    {
        $attributes = $this->class->getListeners(Attributed2Fixture::class);

        self::assertCount(6, $attributes);

        foreach ($attributes as $attribute) {
            self::assertInstanceOf(ListenerContract::class, $attribute);
        }

        self::assertSame(self::VALUE1, $attributes[0]->getEventId());

        self::assertSame(self::VALUE2, $attributes[1]->getEventId());

        self::assertSame(self::VALUE1, $attributes[2]->getEventId());
        self::assertSame(self::VALUE2, $attributes[3]->getEventId());
        self::assertSame(self::VALUE1, $attributes[4]->getEventId());
        self::assertSame(self::VALUE2, $attributes[5]->getEventId());
    }
}
