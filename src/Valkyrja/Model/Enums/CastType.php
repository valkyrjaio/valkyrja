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

namespace Valkyrja\Model\Enums;

/**
 * Enum CastType.
 *
 * @author Melech Mizrachi
 */
enum CastType: string
{
    case string = 'string';
    case int = 'int';
    case float = 'float';
    case double = 'double';
    case bool = 'bool';
    case true = 'true';
    case false = 'false';
    case null = 'null';
    case json = 'json';
    case array = 'array';
    case object = 'object';
    case model = 'model';
    case enum = 'enum';
}
