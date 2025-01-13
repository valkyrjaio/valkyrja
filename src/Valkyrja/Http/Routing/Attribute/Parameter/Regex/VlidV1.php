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

namespace Valkyrja\Http\Routing\Attribute\Parameter\Regex;

use Attribute;
use Valkyrja\Http\Routing\Attribute\Parameter\Regex as ParentAttribute;
use Valkyrja\Http\Routing\Constant\Regex;

/**
 * Attribute VlidV1.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
class VlidV1 extends ParentAttribute
{
    public function __construct()
    {
        parent::__construct(
            value: Regex::VLID_V1,
        );
    }
}
