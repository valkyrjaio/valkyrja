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

namespace Valkyrja\Test\Assert\Contract;

/**
 * Interface Compare.
 *
 * @author Melech Mizrachi
 */
interface Compare extends Asserter
{
    /**
     * Assert if two values are equal.
     */
    public function equals(mixed $expected, mixed $actual): void;

    /**
     * Assert if two values are not equal.
     */
    public function notEquals(mixed $unexpected, mixed $actual): void;
}
