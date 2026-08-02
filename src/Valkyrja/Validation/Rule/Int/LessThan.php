<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Validation\Rule\Int;

use Override;
use Valkyrja\Validation\Rule\Abstract\Rule;

use function is_int;

class LessThan extends Rule
{
    /**
     * @param non-empty-string $errorMessage The error message
     */
    public function __construct(
        mixed $subject,
        protected int $max,
        string $errorMessage
    ) {
        parent::__construct($subject, $errorMessage);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isValid(): bool
    {
        return is_int($this->subject)
            && $this->subject < $this->max;
    }
}
