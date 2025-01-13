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

use Valkyrja\Validation\Rule\Rule;

use function is_string;
use function preg_match;

/**
 * Class Regex.
 *
 * @author Melech Mizrachi
 */
class Regex extends Rule
{
    public function __construct(
        mixed $subject,
        protected string $regex,
        string|null $errorMessage = null
    ) {
        parent::__construct($subject, $errorMessage);
    }

    public function isValid(): bool
    {
        $subject = $this->subject;
        $regex   = $this->regex;

        return is_string($subject) && $subject !== '' && $regex !== '' && preg_match($regex, $subject);
    }

    public function getDefaultErrorMessage(): string
    {
        return "$this->subject must match the given regex $this->regex";
    }
}
