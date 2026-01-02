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

namespace Valkyrja\Container\Enum;

enum InvalidReferenceMode
{
    /**
     * Attempt to create a new instance of the object or return null if not able to.
     */
    case NEW_INSTANCE_OR_NULL;

    /**
     * Attempt to create a new instance of the object or throw an exception of not found if not able to.
     */
    case NEW_INSTANCE_OR_THROW_EXCEPTION;

    /**
     * Return null.
     */
    case NULL;

    /**
     * Throw an exception.
     */
    case THROW_EXCEPTION;
}
