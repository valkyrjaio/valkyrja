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

namespace Valkyrja\Model\Attributes;

use Attribute;
use Valkyrja\Model\Enums\CastType;
use Valkyrja\Type\Contract\Type;

/**
 * Attribute Cast.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class OriginalCast extends Cast
{
    /**
     * @param CastType|class-string<Type> $type The type
     */
    public function __construct(CastType|string $type, public bool $isArray = false)
    {
        parent::__construct($type, false, $isArray);
    }
}
