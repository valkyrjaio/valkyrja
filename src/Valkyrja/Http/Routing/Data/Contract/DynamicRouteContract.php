<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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

    /**
     * Get a parameter by name.
     *
     * @param non-empty-string $name The parameter name
     */
    public function getParameter(string $name): ParameterContract;

    /**
     * Determine if a parameter exists by name.
     *
     * @param non-empty-string $name The parameter name
     */
    public function hasParameter(string $name): bool;
}
