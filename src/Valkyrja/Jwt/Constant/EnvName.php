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

namespace Valkyrja\Jwt\Constant;

/**
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const string DEFAULT_CONFIGURATION = 'JWT_DEFAULT_CONFIGURATION';
    public const string CONFIGURATIONS        = 'JWT_CONFIGURATIONS';

    public const string HS_ALGORITHM     = 'JWT_HS_ALGORITHM';
    public const string HS_ADAPTER_CLASS = 'JWT_HS_ADAPTER_CLASS';
    public const string HS_DRIVER_CLASS  = 'JWT_HS_DRIVER_CLASS';
    public const string HS_KEY           = 'JWT_HS_KEY';

    public const string RS_ALGORITHM     = 'JWT_RS_ALGORITHM';
    public const string RS_ADAPTER_CLASS = 'JWT_RS_ADAPTER_CLASS';
    public const string RS_DRIVER_CLASS  = 'JWT_RS_DRIVER_CLASS';
    public const string RS_PRIVATE_KEY   = 'JWT_RS_PRIVATE_KEY';
    public const string RS_PUBLIC_KEY    = 'JWT_RS_PUBLIC_KEY';
    public const string RS_KEY_PATH      = 'JWT_RS_KEY_PATH';
    public const string RS_PASSPHRASE    = 'JWT_RS_PASSPHRASE';

    public const string EDDSA_ALGORITHM     = 'JWT_EDDSA_ALGORITHM';
    public const string EDDSA_ADAPTER_CLASS = 'JWT_EDDSA_ADAPTER_CLASS';
    public const string EDDSA_DRIVER_CLASS  = 'JWT_EDDSA_DRIVER_CLASS';
    public const string EDDSA_PRIVATE_KEY   = 'JWT_EDDSA_PRIVATE_KEY';
    public const string EDDSA_PUBLIC_KEY    = 'JWT_EDDSA_PUBLIC_KEY';

    public const string NULL_ADAPTER_CLASS = 'JWT_NULL_ADAPTER_CLASS';
    public const string NULL_DRIVER_CLASS  = 'JWT_NULL_DRIVER_CLASS';
}
