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

namespace Valkyrja\Http\Routing\Constant;

/**
 * Constant ParameterName.
 *
 * @author Melech Mizrachi
 */
final class ParameterName
{
    public const ALPHA                = 'alpha';
    public const ALPHA_LOWERCASE      = self::ALPHA . 'Lowercase';
    public const ALPHA_NUM            = self::ALPHA . 'Num';
    public const ALPHA_NUM_UNDERSCORE = self::ALPHA . 'NumUnderscore';
    public const ALPHA_UPPERCASE      = self::ALPHA . 'Uppercase';

    public const ANY = 'any';
    public const NUM = 'num';

    public const ID = 'id';

    public const SLUG = 'slug';

    public const UUID    = 'uuid';
    public const UUID_V1 = 'uuidV1';
    public const UUID_V3 = 'uuidV3';
    public const UUID_V4 = 'uuidV4';
    public const UUID_V5 = 'uuidV5';
    public const UUID_V6 = 'uuidV6';
    public const UUID_V7 = 'uuidV7';
    public const UUID_V8 = 'uuidV8';

    public const ULID = 'ulid';

    public const VLID    = 'vlid';
    public const VLID_V1 = 'vlidV1';
    public const VLID_V2 = 'vlidV2';
    public const VLID_V3 = 'vlidV3';
    public const VLID_V4 = 'vlidV4';
}
