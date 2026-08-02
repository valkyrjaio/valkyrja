<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Output\Contract;

interface FileOutputContract extends OutputContract
{
    /**
     * Get the filepath.
     *
     * @return non-empty-string
     */
    public function getFilepath(): string;

    /**
     * Create a new FileOutput with the specified filepath.
     *
     * @param non-empty-string $filepath The filepath
     */
    public function withFilepath(string $filepath): static;
}
