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

use function is_string;

/**
 * Class IsString.
 *
 * @author Melech Mizrachi
 */
class IsString extends Rule
{
    public function isValid(): bool
    {
        return is_string($this->subject);
    }

    public function getDefaultErrorMessage(): string
    {
        return "$this->subject must be a string";
    }
}
