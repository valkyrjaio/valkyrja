<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Api\Models;

use Valkyrja\Api\JsonData as Contract;

/**
 * Class JsonData.
 *
 * @author Melech Mizrachi
 */
class JsonData implements Contract
{
    use JsonDataTrait;
}
