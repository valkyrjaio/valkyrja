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

namespace Valkyrja\Support\Model\Enums;

use Valkyrja\Support\Enum\JsonSerializable;
use Valkyrja\Support\Enum\JsonSerializableEnum;

/**
 * Enum CastType.
 *
 * @author Melech Mizrachi
 */
enum CastType: string implements JsonSerializableEnum
{
    use JsonSerializable;

    case json   = 'json';
    case array  = 'array';
    case object = 'object';
    case string = 'string';
    case int    = 'int';
    case float  = 'float';
    case bool   = 'bool';
    case model  = 'model';
    case enum   = 'enum';
}