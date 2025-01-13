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

namespace Valkyrja\Validation\Rule\Int;

use Valkyrja\Type\BuiltIn\Support\Integer;
use Valkyrja\Validation\Rule\Rule;

use function is_int;

/**
 * Class GreaterThan.
 *
 * @author Melech Mizrachi
 */
class GreaterThan extends Rule
{
    public function __construct(
        mixed $subject,
        protected int $min,
        string|null $errorMessage = null
    ) {
        parent::__construct($subject, $errorMessage);
    }

    public function isValid(): bool
    {
        $subject = $this->subject;

        return is_int($subject) && Integer::greaterThan($subject, $this->min);
    }

    public function getDefaultErrorMessage(): string
    {
        return "$this->subject must be greater than $this->min";
    }
}
