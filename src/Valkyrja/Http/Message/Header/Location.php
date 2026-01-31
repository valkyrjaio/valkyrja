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

namespace Valkyrja\Http\Message\Header;

use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Header\Value\Contract\ValueContract;

class Location extends Header
{
    /**
     * @param ValueContract|non-empty-string ...$values The location values
     */
    public function __construct(ValueContract|string ...$values)
    {
        parent::__construct(HeaderName::LOCATION, ...$values);
    }
}
