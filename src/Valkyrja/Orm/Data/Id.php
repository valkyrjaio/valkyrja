<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Data;

readonly class Id extends Value
{
    /**
     * @param non-empty-string|int $value The id value
     * @param non-empty-string     $name  The id name
     */
    public function __construct(
        string|int $value,
        string $name = 'id',
    ) {
        parent::__construct($name, $value);
    }
}
