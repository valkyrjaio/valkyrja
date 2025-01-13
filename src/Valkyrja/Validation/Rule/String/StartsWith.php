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
 * Class StartsWith.
 *
 * @author Melech Mizrachi
 */
class StartsWith extends Rule
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
        $subject = $this->subject;

        return is_string($subject) && Str::startsWith($subject, $this->needle);
    }

    public function getDefaultErrorMessage(): string
    {
        return "$this->subject must start with $this->needle";
    }
}
