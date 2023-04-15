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

namespace Valkyrja\Type\Attributes;

use Attribute;
use Valkyrja\Type\Enums\Type;

/**
 * Attribute Union.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Union
{
    /**
     * @var array<int, Intersection|self|Type>
     */
    public array $types = [];

    public function __construct(Type|Intersection|self ...$types)
    {
        $this->types = $types;
    }
}
