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

namespace Valkyrja\Crypt\Constant;

/**
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const string DEFAULT_CONFIGURATION = 'CRYPT_DEFAULT_CONFIGURATION';
    public const string CONFIGURATIONS        = 'CRYPT_CONFIGURATIONS';

    public const string SODIUM_ADAPTER_CLASS = 'CRYPT_SODIUM_ADAPTER_CLASS';
    public const string SODIUM_DRIVER_CLASS  = 'CRYPT_SODIUM_DRIVER_CLASS';
    public const string SODIUM_KEY           = 'CRYPT_SODIUM_KEY';
    public const string SODIUM_KEY_PATH      = 'CRYPT_SODIUM_KEY_PATH';

    public const string NULL_ADAPTER_CLASS = 'CRYPT_NULL_ADAPTER_CLASS';
    public const string NULL_DRIVER_CLASS  = 'CRYPT_NULL_DRIVER_CLASS';
    public const string NULL_KEY           = 'CRYPT_NULL_KEY';
}
