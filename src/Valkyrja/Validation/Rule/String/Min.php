<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Validation\Rule\String;

use Override;
use Valkyrja\Type\BuiltIn\Support\Str;
use Valkyrja\Validation\Rule\Rule;

use function is_string;

/**
 * Class Min.
 *
 * @author Melech Mizrachi
 */
class Min extends Rule
{
    public function __construct(
        mixed $subject,
        protected int $min,
        string|null $errorMessage = null
    ) {
        parent::__construct($subject, $errorMessage);
    }

    #[Override]
    public function isValid(): bool
    {
        /** @var mixed $subject */
        $subject = $this->subject;

        return is_string($subject) && Str::min($subject, $this->min);
    }

    #[Override]
    public function getDefaultErrorMessage(): string
    {
        return "Must be longer than $this->min";
    }
}
