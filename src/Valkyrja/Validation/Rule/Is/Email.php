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

use Override;
use Valkyrja\Type\BuiltIn\Support\Str;
use Valkyrja\Validation\Rule\Abstract\Rule;

use function is_string;

class Email extends Rule
{
    #[Override]
    public function isValid(): bool
    {
        return is_string($this->subject)
            && Str::isEmail($this->subject);
    }

    #[Override]
    public function getDefaultErrorMessage(): string
    {
        return 'Must be a valid email';
    }
}
