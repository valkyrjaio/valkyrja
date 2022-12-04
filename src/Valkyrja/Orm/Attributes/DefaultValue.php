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

namespace Valkyrja\Orm\Attributes;

use Attribute;

/**
 * Attribute DefaultValue.
 *
 * @author Melech Mizrachi
 */
#[Attribute]
class DefaultValue
{
    /**
     * DefaultValue constructor.
     *
     * @param mixed $value          The default value
     * @param bool  $shouldBeQuoted [optional] Whether the value should be quoted
     */
    public function __construct(
        public mixed $value,
        public bool $shouldBeQuoted = true
    ) {
    }
}
