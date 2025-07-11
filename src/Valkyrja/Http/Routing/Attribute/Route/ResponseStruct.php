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

namespace Valkyrja\Http\Routing\Attribute\Route;

use Attribute;
use Valkyrja\Http\Struct\Response\Contract\ResponseStruct as RoutingResponseStruct;

/**
 * Attribute ResponseStruct.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_METHOD)]
class ResponseStruct
{
    /**
     * @param class-string<RoutingResponseStruct> $name
     */
    public function __construct(
        public string $name
    ) {
    }
}
