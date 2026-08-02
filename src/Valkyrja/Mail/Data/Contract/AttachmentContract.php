<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Mail\Data\Contract;

interface AttachmentContract
{
    /**
     * Get the path.
     *
     * @return non-empty-string
     */
    public function getPath(): string;

    /**
     * Create a new instance with the given path.
     *
     * @param non-empty-string $path The path
     */
    public function withPath(string $path): static;

    /**
     * Determine if there is a name.
     */
    public function hasName(): bool;

    /**
     * Get the name.
     */
    public function getName(): string;

    /**
     * Create a new instance with the given name.
     */
    public function withName(string $name): static;
}
