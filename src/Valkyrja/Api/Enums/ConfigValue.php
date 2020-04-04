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

namespace Valkyrja\Api\Enums;

use Valkyrja\Api\Models\Json;
use Valkyrja\Api\Models\JsonData;
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;

/**
 * Enum ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const JSON_MODEL      = Json::class;
    public const JSON_DATA_MODEL = JsonData::class;

    public static array $defaults = [
        CKP::JSON_MODEL      => self::JSON_MODEL,
        CKP::JSON_DATA_MODEL => self::JSON_DATA_MODEL,
    ];
}
