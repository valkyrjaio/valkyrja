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

interface PropertyDispatchContract extends ClassDispatchContract
{
    /**
     * Get the property.
     *
     * @return non-empty-string
     */
    public function getProperty(): string;

    /**
     * Create a new dispatch with the specified property.
     *
     * @param non-empty-string $property The property
     */
    public function withProperty(string $property): static;

    /**
     * Determine whether this is a static property.
     */
    public function isStatic(): bool;

    /**
     * Create a new dispatch with whether this is a static property.
     *
     * @param bool $isStatic The static flag
     */
    public function withIsStatic(bool $isStatic): static;
}
