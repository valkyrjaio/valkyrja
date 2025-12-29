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

namespace Valkyrja\Tests\Unit\Cli\Routing\Collector;

use ReflectionException;
use Valkyrja\Attribute\Collector\Collector;
use Valkyrja\Cli\Routing\Collector\AttributeCollector;
use Valkyrja\Reflection\Reflector\Reflector;
use Valkyrja\Tests\Classes\Cli\Routing\Command\CommandClass;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the AttributeCollector class.
 *
 * @author Melech Mizrachi
 */
class AttributeCollectorTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testDefaults(): void
    {
        $collector = new AttributeCollector(
            attributes: new Collector(),
            reflection: new Reflector()
        );

        self::assertEmpty($collector->getRoutes(self::class));
    }

    /**
     * @throws ReflectionException
     */
    public function testGetCommands(): void
    {
        $collector = new AttributeCollector(
            attributes: new Collector(),
            reflection: new Reflector()
        );

        $commands = $collector->getRoutes(CommandClass::class);

        self::assertNotEmpty($commands);
        self::assertCount(1, $commands);
    }
}
