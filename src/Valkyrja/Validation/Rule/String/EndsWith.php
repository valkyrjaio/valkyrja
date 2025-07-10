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

use Valkyrja\Type\BuiltIn\Support\Str;
use Valkyrja\Validation\Rule\Rule;

use function is_string;

/**
 * Class EndsWith.
 *
 * @author Melech Mizrachi
 */
class EndsWith extends Rule
{
    public function __construct(
        mixed $subject,
        protected string $needle,
        string|null $errorMessage = null
    ) {
        parent::__construct($subject, $errorMessage);
    }

    public function isValid(): bool
    {
        return is_string($this->subject)
            && Str::endsWith($this->subject, $this->needle);
    }

    public function getDefaultErrorMessage(): string
    {
        return "Must end with $this->needle";
    }
}
