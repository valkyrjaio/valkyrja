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

namespace Valkyrja\Http\Routing\Data\Contract;

interface DynamicRouteContract extends RouteContract
{
    /**
     * Get the regex.
     */
    public function getRegex(): string;

    /**
     * Set the regex.
     */
    public function withRegex(string $regex): static;

    /**
     * Get the parameters.
     *
     * @return array<array-key, ParameterContract>
     */
    public function getParameters(): array;

    /**
     * Create a new route with given parameters.
     *
     * @param ParameterContract ...$parameters The parameter
     */
    public function withParameters(ParameterContract ...$parameters): static;

    /**
     * Create a new route with added parameters.
     *
     * @param ParameterContract ...$parameters The parameter
     */
    public function withAddedParameters(ParameterContract ...$parameters): static;
}
