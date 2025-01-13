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

namespace Valkyrja\Http\Routing\Attribute\Route\RequestMethod;

use Attribute;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Attribute\Route\RequestMethod as ParentAttribute;

/**
 * Attribute Any.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL)]
class Any extends ParentAttribute
{
    public function __construct()
    {
        parent::__construct(
            RequestMethod::CONNECT,
            RequestMethod::DELETE,
            RequestMethod::GET,
            RequestMethod::HEAD,
            RequestMethod::OPTIONS,
            RequestMethod::PATCH,
            RequestMethod::POST,
            RequestMethod::PUT,
            RequestMethod::TRACE,
        );
    }
}
