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
use Valkyrja\Http\Message\Enum\RequestMethod as RequestMethodEnum;

#[Attribute(Attribute::TARGET_METHOD)]
class RequestMethod
{
    /** @var RequestMethodEnum[] */
    public array $requestMethods = [];

    public function __construct(RequestMethodEnum ...$requestMethods)
    {
        $this->requestMethods = $requestMethods;
    }
}
