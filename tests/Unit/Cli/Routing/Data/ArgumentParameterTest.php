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

use Valkyrja\Cli\Routing\Data\ArgumentParameter;
use Valkyrja\Cli\Routing\Enum\ArgumentMode;
use Valkyrja\Cli\Routing\Enum\ArgumentValueMode;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the ArgumentParameter class.
 */
class ArgumentParameterTest extends TestCase
{
    public function testDefaults(): void
    {
        $parameter = new ArgumentParameter('test-arg', 'Test Argument');

        self::assertSame('test-arg', $parameter->getName());
        self::assertSame('Test Argument', $parameter->getDescription());
        self::assertNull($parameter->getCast());
        self::assertSame(ArgumentMode::OPTIONAL, $parameter->getMode());
        self::assertSame(ArgumentValueMode::DEFAULT, $parameter->getValueMode());
        self::assertEmpty($parameter->getArguments());
    }
}
