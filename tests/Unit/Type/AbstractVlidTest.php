<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Type;

use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Enums\VlidVersion;

abstract class AbstractVlidTest extends TestCase
{
    protected function ensureVersionInGeneratedString(VlidVersion $version, string $generated): void
    {
        $this->assertSame((string) $version->value, $generated[13]);
    }
}
