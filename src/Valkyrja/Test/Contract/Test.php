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

namespace Valkyrja\Test\Contract;

use Closure;
use Valkyrja\Test\Assert\Assert;

/**
 * Interface Test.
 *
 * @author Melech Mizrachi
 */
interface Test
{
    /**
     * Get the test's assertions.
     */
    public function getAssert(): Assert;

    /**
     * Get the test's name.
     */
    public function getName(): string;

    /**
     * Determine whether this test should be skipped.
     */
    public function shouldSkip(): bool;

    /**
     * Run the test.
     */
    public function run(Closure $closure, array $data = []): void;
}
