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

namespace Valkyrja\Validation\Rule\Is;

use Valkyrja\Validation\Rule\Rule;

/**
 * Class Equal.
 *
 * @author Melech Mizrachi
 */
class Equal extends Rule
{
    public function __construct(
        mixed $subject,
        protected mixed $value,
        string|null $errorMessage = null
    ) {
        parent::__construct($subject, $errorMessage);
    }

    public function isValid(): bool
    {
        return $this->subject === $this->value;
    }

    public function getDefaultErrorMessage(): string
    {
        return "$this->subject must equal $this->value";
    }
}
