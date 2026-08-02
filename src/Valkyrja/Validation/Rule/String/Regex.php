<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Validation\Rule\String;

use Override;
use Valkyrja\Validation\Rule\Abstract\Rule;

use function is_string;
use function preg_match;

class Regex extends Rule
{
    /**
     * @param non-empty-string $regex        The regex
     * @param non-empty-string $errorMessage The error message
     */
    public function __construct(
        mixed $subject,
        protected string $regex,
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
        $regex   = $this->regex;

        return is_string($this->subject)
            && $this->subject !== ''
            && preg_match($regex, $this->subject);
    }
}
