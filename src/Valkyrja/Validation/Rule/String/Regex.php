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
use Valkyrja\Validation\Rule\Abstract\Rule;

use function is_string;
use function preg_match;

class Regex extends Rule
{
    /**
     * @param non-empty-string      $regex        The regex
     * @param non-empty-string|null $errorMessage The error message
     */
    public function __construct(
        mixed $subject,
        protected string $regex,
        string|null $errorMessage = null
    ) {
        parent::__construct($subject, $errorMessage);
    }

    #[Override]
    public function isValid(): bool
    {
        $regex   = $this->regex;

        return is_string($this->subject)
            && $this->subject !== ''
            && preg_match($regex, $this->subject);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDefaultErrorMessage(): string
    {
        return "Must match the given regex $this->regex";
    }
}
