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
use Valkyrja\Validation\Rule\Abstract\Rule;

use function is_string;

class Contains extends Rule
{
    public function __construct(
        mixed $subject,
        protected string $needle,
        string|null $errorMessage = null
    ) {
        parent::__construct($subject, $errorMessage);
    }

    #[Override]
    public function isValid(): bool
    {
        return is_string($this->subject)
            && Str::contains($this->subject, $this->needle);
    }

    #[Override]
    public function getDefaultErrorMessage(): string
    {
        return "Must contain $this->needle";
    }
}
