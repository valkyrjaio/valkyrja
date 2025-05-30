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

namespace Valkyrja\Type\Model\Attribute;

use Attribute;
use Valkyrja\Type\Contract\Type;
use Valkyrja\Type\Enum\CastType;

/**
 * Attribute Cast.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class ArrayCast extends Cast
{
    /**
     * @param CastType|class-string<Type> $type The type
     */
    public function __construct(CastType|string $type, bool $convert = true)
    {
        parent::__construct($type, $convert, true);
    }
}
