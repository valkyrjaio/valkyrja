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
use Valkyrja\Routing\Constants\Regex;
use Valkyrja\Type\Model\Data\Cast;

/**
 * Attribute Parameter.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Optional extends Parameter
{
    public function __construct(
        string $name,
        string|null $regex = null,
        Cast|null $cast = null,
        bool $shouldCapture = true,
        mixed $default = null,
    ) {
        parent::__construct(
            name: $name,
            regex: $regex ?? Regex::ANY,
            cast: $cast,
            shouldCapture: $shouldCapture,
            default: $default,
        );
    }
}
