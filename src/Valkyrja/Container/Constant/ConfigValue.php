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

namespace Valkyrja\Container\Constant;

use Valkyrja\Config\Constant\ConfigKeyPart as CKP;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const ALIASES          = [];
    public const SERVICES         = [];
    public const CONTEXT_SERVICES = [];
    public const PROVIDERS        = [
        Provider::DISPATCHER,
        Provider::EVENT,
        Provider::ANNOTATION,
        Provider::API,
        Provider::ATTRIBUTES,
        Provider::AUTH,
        Provider::BROADCAST,
        Provider::CACHE,
        Provider::CLIENT,
        Provider::CONSOLE,
        Provider::CONTAINER,
        Provider::CRYPT,
        Provider::FILESYSTEM,
        Provider::HTTP_MESSAGE,
        Provider::HTTP_SERVER,
        Provider::HTTP_MIDDLEWARE,
        Provider::JWT,
        Provider::LOG,
        Provider::MAIL,
        Provider::NOTIFICATION,
        Provider::ORM,
        Provider::PATH,
        Provider::REFLECTION,
        Provider::HTTP_ROUTING,
        Provider::SESSION,
        Provider::SMS,
        Provider::VIEW,
    ];
    public const DEV_PROVIDERS    = [];
    public const USE_ANNOTATIONS  = false;
    public const USE_ATTRIBUTES   = true;
    public const FILE_PATH        = '';
    public const CACHE_FILE_PATH  = '';
    public const USE_CACHE_FILE   = false;

    /** @var array<string, mixed> */
    public static array $defaults = [
        CKP::ALIASES          => self::ALIASES,
        CKP::SERVICES         => self::SERVICES,
        CKP::CONTEXT_SERVICES => self::CONTEXT_SERVICES,
        CKP::PROVIDERS        => self::PROVIDERS,
        CKP::DEV_PROVIDERS    => self::DEV_PROVIDERS,
        CKP::USE_ANNOTATIONS  => self::USE_ANNOTATIONS,
        CKP::USE_ATTRIBUTES   => self::USE_ATTRIBUTES,
        CKP::FILE_PATH        => self::FILE_PATH,
        CKP::CACHE_FILE_PATH  => self::CACHE_FILE_PATH,
        CKP::USE_CACHE        => self::USE_CACHE_FILE,
    ];
}
