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

use Override;
use Valkyrja\Type\BuiltIn\Support\Integer;
use Valkyrja\Validation\Rule\Rule;

use function is_int;

/**
 * Class LessThan.
 *
 * @author Melech Mizrachi
 */
class LessThan extends Rule
{
    public function __construct(
        mixed $subject,
        protected int $max,
        string|null $errorMessage = null
    ) {
        parent::__construct($subject, $errorMessage);
    }

    #[Override]
    public function isValid(): bool
    {
        return is_int($this->subject)
            && Integer::lessThan($this->subject, $this->max);
    }

    #[Override]
    public function getDefaultErrorMessage(): string
    {
        return "Must be less than $this->max";
    }
}
