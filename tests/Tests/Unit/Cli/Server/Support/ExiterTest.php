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

namespace Valkyrja\Tests\Unit\Cli\Server\Support;

use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Server\Support\Exiter;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class ExiterTest extends TestCase
{
    public function testDefault(): void
    {
        Exiter::freeze();

        ob_start();
        Exiter::exit();
        $code = ob_get_clean();

        Exiter::unfreeze();

        self::assertSame('0', $code);
    }

    public function testExitCode(): void
    {
        $code = ExitCode::AUTO_EXIT->value;

        Exiter::freeze();

        ob_start();
        Exiter::exit($code);
        $code = ob_get_clean();

        Exiter::unfreeze();

        self::assertSame((string) $code, $code);
    }
}
