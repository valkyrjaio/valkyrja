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

namespace Valkyrja\Type\Data;

use Valkyrja\Type\Contract\TypeContract;
use Valkyrja\Type\Enum\CastType;

/**
 * Data OriginalCast.
 */
class OriginalCast extends Cast
{
    /**
     * @param CastType|class-string<TypeContract> $type The type
     */
    public function __construct(CastType|string $type, public bool $isArray = false)
    {
        parent::__construct($type, false, $isArray);
    }
}
