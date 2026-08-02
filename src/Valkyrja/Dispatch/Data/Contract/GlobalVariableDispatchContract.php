<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Dispatch\Data\Contract;

interface GlobalVariableDispatchContract extends DispatchContract
{
    /**
     * Get the variable.
     *
     * @return non-empty-string
     */
    public function getVariable(): string;

    /**
     * Create a new dispatch with the specified variable.
     *
     * @param non-empty-string $variable
     */
    public function withVariable(string $variable): static;
}
