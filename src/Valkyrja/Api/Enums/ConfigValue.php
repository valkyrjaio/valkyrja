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

namespace Valkyrja\Api\Enums;

use Valkyrja\Api\Models\Json;
use Valkyrja\Api\Models\JsonData;
use Valkyrja\Enum\Enums\Enum;

/**
 * Enum ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue extends Enum
{
    public const JSON_MODEL      = Json::class;
    public const JSON_DATA_MODEL = JsonData::class;
}
