<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Model\Trait;

use Valkyrja\Type\Object\Factory\ObjectFactory;

use function array_merge;

trait ProtectedExposable
{
    use Exposable;

    /**
     * Get all properties.
     *
     * @return array<string, mixed>
     */
    protected function internalGetAllProperties(): array
    {
        return array_merge(ObjectFactory::getProperties($this), $this->internalExposed);
    }
}
