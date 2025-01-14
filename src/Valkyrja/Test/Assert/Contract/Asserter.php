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

use Valkyrja\Test\Exception\AssertFailureException;
use Valkyrja\Test\Exception\AssertWarningException;

/**
 * Interface Asserter.
 *
 * @author Melech Mizrachi
 */
interface Asserter
{
    /**
     * Get the assertions.
     *
     * @return string[]
     */
    public function getAssertions(): array;

    /**
     * Get the errors.
     *
     * @return AssertFailureException[]
     */
    public function getErrors(): array;

    /**
     * Get the successes.
     *
     * @return string[]
     */
    public function getSuccesses(): array;

    /**
     * Get the warnings.
     *
     * @return AssertWarningException[]
     */
    public function getWarnings(): array;

    /**
     * Get a result type by property name.
     */
    public function __get(string $name): mixed;
}
