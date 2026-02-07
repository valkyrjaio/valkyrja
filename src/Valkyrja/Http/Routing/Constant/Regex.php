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

use Valkyrja\Type\Ulid\Factory\UlidFactory;
use Valkyrja\Type\Uuid\Factory\UuidFactory;
use Valkyrja\Type\Uuid\Factory\UuidV1Factory;
use Valkyrja\Type\Uuid\Factory\UuidV3Factory;
use Valkyrja\Type\Uuid\Factory\UuidV4Factory;
use Valkyrja\Type\Uuid\Factory\UuidV5Factory;
use Valkyrja\Type\Uuid\Factory\UuidV6Factory;
use Valkyrja\Type\Uuid\Factory\UuidV7Factory;
use Valkyrja\Type\Uuid\Factory\UuidV8Factory;
use Valkyrja\Type\Vlid\Factory\VlidFactory;
use Valkyrja\Type\Vlid\Factory\VlidV1Factory;
use Valkyrja\Type\Vlid\Factory\VlidV2Factory;
use Valkyrja\Type\Vlid\Factory\VlidV3Factory;
use Valkyrja\Type\Vlid\Factory\VlidV4Factory;

final class Regex
{
    public const string PATH = '\/';
    public const string ANY  = '.*';
    public const string NUM  = '\d+';

    public const string ID = self::NUM;

    public const string SLUG = '[a-zA-Z0-9-]+';

    public const string UUID    = UuidFactory::REGEX;
    public const string UUID_V1 = UuidV1Factory::REGEX;
    public const string UUID_V3 = UuidV3Factory::REGEX;
    public const string UUID_V4 = UuidV4Factory::REGEX;
    public const string UUID_V5 = UuidV5Factory::REGEX;
    public const string UUID_V6 = UuidV6Factory::REGEX;
    public const string UUID_V7 = UuidV7Factory::REGEX;
    public const string UUID_V8 = UuidV8Factory::REGEX;

    public const string ULID = UlidFactory::REGEX;

    public const string VLID    = VlidFactory::REGEX;
    public const string VLID_V1 = VlidV1Factory::REGEX;
    public const string VLID_V2 = VlidV2Factory::REGEX;
    public const string VLID_V3 = VlidV3Factory::REGEX;
    public const string VLID_V4 = VlidV4Factory::REGEX;

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
