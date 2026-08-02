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
use Valkyrja\Type\String\Factory\StringFactory;
use Valkyrja\Validation\Rule\Abstract\Rule;

use function is_string;

class Contains extends Rule
{
    /**
     * @param non-empty-string $needle       The needle
     * @param non-empty-string $errorMessage The error message
     */
    public function __construct(
        mixed $subject,
        protected string $needle,
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
        return is_string($this->subject)
            && StringFactory::contains($this->subject, $this->needle);
    }
}
