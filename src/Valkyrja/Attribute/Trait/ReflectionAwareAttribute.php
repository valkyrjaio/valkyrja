<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Attribute\Trait;

use Reflector;

trait ReflectionAwareAttribute
{
    protected Reflector $reflection;

    /**
     * Get the reflection.
     */
    public function getReflection(): Reflector
    {
        return $this->reflection;
    }

    /**
     * Set the reflection.
     *
     * @param Reflector $reflection The reflection
     */
    public function setReflection(Reflector $reflection): void
    {
        $this->reflection = $reflection;
    }
}
