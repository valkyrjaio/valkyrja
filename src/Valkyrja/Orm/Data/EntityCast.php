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

namespace Valkyrja\Orm\Data;

use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Type\Data\Cast;
use Valkyrja\Type\Enum\CastType;

class EntityCast extends Cast
{
    /**
     * @param CastType|class-string<EntityContract> $type          The type
     * @param string[]|null                         $relationships [optional] The relationships
     */
    public function __construct(
        CastType|string $type,
        public string|null $column = null,
        public array|null $relationships = null,
        bool $convert = true,
        bool $isArray = false
    ) {
        parent::__construct($type, $convert, $isArray);
    }
}
