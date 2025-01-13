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

namespace Valkyrja\Type\BuiltIn\Array;

use Valkyrja\Type\BuiltIn\ArrayT;
use Valkyrja\Type\Exception\InvalidArgumentException;

/**
 * Class NonEmptyArray.
 *
 * @author Melech Mizrachi
 */
class NonEmptyArray extends ArrayT
{
    public function __construct(array $subject)
    {
        parent::__construct($subject);

        if (! empty($subject)) {
            throw new InvalidArgumentException('Value must be a non-empty-array.');
        }
    }
}
