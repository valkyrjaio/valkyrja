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

namespace Valkyrja\Routing\Attributes\Parameter;

use Attribute;
use Valkyrja\Routing\Attributes\Parameter;
use Valkyrja\Routing\Constants\ParameterName;
use Valkyrja\Routing\Constants\Regex;
use Valkyrja\Routing\Data\EntityCast;

/**
 * Attribute Entity.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Entity extends Parameter
{
    public function __construct(
        EntityCast|null $cast = null,
        string|null $name = null,
        string|null $regex = null,
        bool $isOptional = false,
        bool $shouldCapture = true,
        mixed $default = null,
    ) {
        parent::__construct(
            name         : $name ?? ParameterName::ID,
            regex        : $regex ?? Regex::ANY,
            cast         : $cast,
            isOptional   : $isOptional,
            shouldCapture: $shouldCapture,
            default      : $default,
        );
    }
}
