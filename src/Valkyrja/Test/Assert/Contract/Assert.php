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
 * Interface Assert.
 *
 * @author Melech Mizrachi
 *
 * @property Exceptions $exceptions
 * @property Str        $string
 */
interface Assert extends Asserter
{
    /**
     * Get the compare asserter.
     */
    public function compare(): Compare;

    /**
     * Get the exceptions asserter.
     */
    public function exceptions(): Exceptions;

    /**
     * Get the string asserter.
     */
    public function string(): Str;

    /**
     * Add more asserters.
     *
     * @param array<string, class-string<Asserter>> $asserters
     */
    public function withAsserters(array $asserters): void;

    /**
     * Get an asserter by name.
     */
    public function __get(string $name): mixed;

    /**
     * Call an asserter by name.
     *
     * @param string                  $name      The name
     * @param array<array-key, mixed> $arguments The arguments
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments): mixed;
}
