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
use Valkyrja\Type\String\Factory\StringFactory;
use Valkyrja\Validation\Rule\Abstract\Rule;

use function is_string;

class Alpha extends Rule
{
    #[Override]
    public function isValid(): bool
    {
        return is_string($this->subject)
            && StringFactory::isAlphabetic($this->subject);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDefaultErrorMessage(): string
    {
        return 'Must be alphabetic';
    }
}
