<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\String;

use Valkyrja\Type\String\Throwable\Exception\StringInvalidEmptyStringException;

class NonEmptyString extends StringT
{
    public function __construct(string $subject)
    {
        if ($subject === '') {
            throw new StringInvalidEmptyStringException('Value must be a non-empty-string.');
        }

        parent::__construct($subject);
    }
}
