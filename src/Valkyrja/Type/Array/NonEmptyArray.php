<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Array;

use Valkyrja\Type\Array\Throwable\Exception\ArrayInvalidNonEmptyException;

class NonEmptyArray extends ArrayT
{
    /**
     * @param array<array-key, mixed> $subject The array
     */
    public function __construct(array $subject)
    {
        if ($subject === []) {
            throw new ArrayInvalidNonEmptyException('Value must be a non-empty-array.');
        }

        parent::__construct($subject);
    }
}
