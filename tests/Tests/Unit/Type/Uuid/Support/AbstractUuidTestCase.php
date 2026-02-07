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

namespace Valkyrja\Tests\Unit\Type\Uuid\Support;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Uuid\Enum\Version;

abstract class AbstractUuidTestCase extends TestCase
{
    protected function ensureVersionInGeneratedString(Version $version, string $generated): void
    {
        self::assertSame((string) $version->value, $generated[14]);
    }
}
