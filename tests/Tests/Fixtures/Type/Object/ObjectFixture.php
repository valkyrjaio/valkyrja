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

namespace Valkyrja\Tests\Fixtures\Type\Object;

/**
 * A plain object with a single writable property, for the object type tests.
 */
final class ObjectFixture
{
    public function __construct(
        public string $foo = 'test'
    ) {
    }
}
