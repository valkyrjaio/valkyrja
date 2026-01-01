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

use Valkyrja\Type\Ulid\Support\Ulid;
use Valkyrja\Type\Uuid\Support\Uuid;
use Valkyrja\Type\Uuid\Support\UuidV1;
use Valkyrja\Type\Uuid\Support\UuidV3;
use Valkyrja\Type\Uuid\Support\UuidV4;
use Valkyrja\Type\Uuid\Support\UuidV5;
use Valkyrja\Type\Uuid\Support\UuidV6;
use Valkyrja\Type\Uuid\Support\UuidV7;
use Valkyrja\Type\Uuid\Support\UuidV8;
use Valkyrja\Type\Vlid\Support\Vlid;
use Valkyrja\Type\Vlid\Support\VlidV1;
use Valkyrja\Type\Vlid\Support\VlidV2;
use Valkyrja\Type\Vlid\Support\VlidV3;
use Valkyrja\Type\Vlid\Support\VlidV4;

/**
 * Constant Regex.
 */
final class Regex
{
    public const string PATH = '\/';
    public const string ANY  = '.*';
    public const string NUM  = '\d+';

    public const string ID = self::NUM;

    public const string SLUG = '[a-zA-Z0-9-]+';

    public const string UUID    = Uuid::REGEX;
    public const string UUID_V1 = UuidV1::REGEX;
    public const string UUID_V3 = UuidV3::REGEX;
    public const string UUID_V4 = UuidV4::REGEX;
    public const string UUID_V5 = UuidV5::REGEX;
    public const string UUID_V6 = UuidV6::REGEX;
    public const string UUID_V7 = UuidV7::REGEX;
    public const string UUID_V8 = UuidV8::REGEX;

    public const string ULID = Ulid::REGEX;

    public const string VLID    = Vlid::REGEX;
    public const string VLID_V1 = VlidV1::REGEX;
    public const string VLID_V2 = VlidV2::REGEX;
    public const string VLID_V3 = VlidV3::REGEX;
    public const string VLID_V4 = VlidV4::REGEX;

    public const string ALPHA                = '[a-zA-Z]+';
    public const string ALPHA_LOWERCASE      = '[a-z]+';
    public const string ALPHA_UPPERCASE      = '[A-Z]+';
    public const string ALPHA_NUM            = '[a-zA-Z0-9]+';
    public const string ALPHA_NUM_UNDERSCORE = '\w+';

    public const string START = '/^';
    public const string END   = '$/';

    public const string START_CAPTURE_GROUP          = '(';
    public const string START_NON_CAPTURE_GROUP      = '(?:';
    public const string START_OPTIONAL_CAPTURE_GROUP = self::START_NON_CAPTURE_GROUP . self::PATH . self::END_OPTIONAL_CAPTURE_GROUP;
    public const string END_CAPTURE_GROUP            = ')';
    public const string END_OPTIONAL_CAPTURE_GROUP   = ')?';

    public const string START_CAPTURE_GROUP_NAME = '?<';
    public const string END_CAPTURE_GROUP_NAME   = '>';
}
