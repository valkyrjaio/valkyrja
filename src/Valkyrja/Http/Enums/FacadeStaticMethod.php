<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Enums;

use Valkyrja\Enum\Enums\Enum;

/**
 * Enum FacadeStaticMethod.
 *
 * @author Melech Mizrachi
 */
final class FacadeStaticMethod extends Enum
{
    public const MAKE          = 'make';
    public const MAKE_JSON     = 'makeJson';
    public const MAKE_REDIRECT = 'makeRedirect';
}
