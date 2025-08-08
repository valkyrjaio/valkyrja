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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Factory;

use Valkyrja\Cli\Interaction\Factory\ArgumentFactory;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the ArgumentFactory class.
 *
 * @author Melech Mizrachi
 */
class ArugmentFactoryTest extends TestCase
{
    public function testFromArg(): void
    {
        $arg  = 'value';
        $arg2 = 'value2';

        $argument  = ArgumentFactory::fromArg(arg: $arg);
        $argument2 = ArgumentFactory::fromArg(arg: $arg2);

        self::assertNotSame($argument, $argument2);
        self::assertSame($arg, $argument->getValue());
        self::assertSame($arg2, $argument2->getValue());
    }
}
