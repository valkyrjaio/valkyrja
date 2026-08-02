<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Format\Contract;

interface FormatContract
{
    /**
     * Get the set code.
     *
     * @return non-empty-string
     */
    public function getSetCode(): string;

    /**
     * Create a new format with the specified set code.
     *
     * @param non-empty-string $setCode The set code
     */
    public function withSetCode(string $setCode): static;

    /**
     * Get the unset code.
     *
     * @return non-empty-string
     */
    public function getUnsetCode(): string;

    /**
     * Create a new format with the specified unset code.
     *
     * @param non-empty-string $unsetCode The unset code
     */
    public function withUnsetCode(string $unsetCode): static;
}
