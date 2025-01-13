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

namespace Valkyrja\Type\BuiltIn\String;

use Valkyrja\Type\BuiltIn\StringT;
use Valkyrja\Type\Exception\InvalidArgumentException;

/**
 * Class NonEmptyString.
 *
 * @author Melech Mizrachi
 */
class NonEmptyString extends StringT
{
    public function __construct(string $subject)
    {
        parent::__construct($subject);

        if (! empty($subject)) {
            throw new InvalidArgumentException('Value must be a non-empty-string.');
        }
    }
}
