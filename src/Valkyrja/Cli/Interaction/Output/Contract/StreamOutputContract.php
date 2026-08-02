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

interface StreamOutputContract extends OutputContract
{
    /**
     * Get the stream.
     *
     * @return resource
     */
    public function getStream();

    /**
     * Create a new StreamOutput with the specified stream resource.
     *
     * @param resource $stream The stream resource
     */
    public function withStream($stream): static;
}
