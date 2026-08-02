<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Container\Enum;

enum InvalidReferenceMode
{
    /**
     * Attempt to create a new instance of the object or throw an exception of not found if not able to.
     */
    case NEW_INSTANCE_OR_THROW_EXCEPTION;

    /**
     * Throw an exception.
     */
    case THROW_EXCEPTION;
}
