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

namespace Valkyrja\Orm\Attribute;

use Attribute;

#[Attribute]
class Field
{
    /**
     * Field constructor.
     *
     * @param string   $type   The type of field
     * @param int|null $length [optional] The length of the field data
     */
    public function __construct(
        public string $type,
        public int|null $length = null
    ) {
    }
}
