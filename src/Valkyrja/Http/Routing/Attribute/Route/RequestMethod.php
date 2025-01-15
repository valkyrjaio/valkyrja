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
use Valkyrja\Http\Message\Enum\RequestMethod as Enum;

/**
 * Attribute RequestMethod.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL)]
class RequestMethod
{
    /** @var Enum[] */
    public array $methods = [];

    /**
     * @param Enum ...$type
     */
    public function __construct(Enum ...$type)
    {
        $this->methods = $type;
    }
}
