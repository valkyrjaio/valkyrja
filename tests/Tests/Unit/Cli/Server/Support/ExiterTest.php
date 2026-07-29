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
use Valkyrja\Tests\Fixtures\Cli\Server\Support\ExiterRecorderFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function ob_get_clean;
use function ob_start;

final class ExiterTest extends TestCase
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
        $output = ob_get_clean();

        Exiter::unfreeze();

        self::assertSame((string) $code, $output);
    }

    /**
     * The unfrozen arm calls exit(), which would terminate the test process, so
     * it is only reachable through a fixture overriding the exit seam.
     */
    public function testExitsWhenNotFrozen(): void
    {
        $code = ExitCode::AUTO_EXIT->value;

        ExiterRecorderFixture::reset();

        ob_start();
        ExiterRecorderFixture::exit($code);
        $output = ob_get_clean();

        self::assertSame($code, ExiterRecorderFixture::$exitedWithCode);
        self::assertSame('', $output);
    }

    public function testDoesNotExitWhenFrozen(): void
    {
        $code = ExitCode::ERROR->value;

        ExiterRecorderFixture::reset();
        ExiterRecorderFixture::freeze();

        ob_start();
        ExiterRecorderFixture::exit($code);
        $output = ob_get_clean();

        ExiterRecorderFixture::unfreeze();

        self::assertNull(ExiterRecorderFixture::$exitedWithCode);
        self::assertSame((string) $code, $output);
    }
}
