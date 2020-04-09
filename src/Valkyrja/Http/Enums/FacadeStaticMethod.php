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

namespace Valkyrja\Http\Enums;

use Valkyrja\Support\Enum\Enum;

/**
 * Enum FacadeStaticMethod.
 *
 * @author Melech Mizrachi
 */
final class FacadeStaticMethod extends Enum
{
    public const MAKE            = 'make';
    public const CREATE_RESPONSE = 'createResponse';
    public const CREATE_JSON     = 'createJsonResponse';
    public const CREATE_REDIRECT = 'createRedirectResponse';
}
