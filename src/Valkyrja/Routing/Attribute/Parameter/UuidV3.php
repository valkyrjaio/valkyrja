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

namespace Valkyrja\Routing\Attribute\Parameter;

use Attribute;
use Valkyrja\Routing\Attribute\Parameter;
use Valkyrja\Routing\Constant\ParameterName;
use Valkyrja\Routing\Constant\Regex;
use Valkyrja\Type\Model\Data\Cast;

/**
 * Attribute UuidV3.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class UuidV3 extends Parameter
{
    public function __construct(
        string|null $name = null,
        Cast|null $cast = null,
        bool $isOptional = false,
        bool $shouldCapture = true,
        mixed $default = null,
    ) {
        parent::__construct(
            name: $name ?? ParameterName::UUID_V3,
            regex: Regex::UUID_V3,
            cast: $cast,
            isOptional: $isOptional,
            shouldCapture: $shouldCapture,
            default: $default,
        );
    }
}
