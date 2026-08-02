<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Auth\Entity\Trait;

trait VerifiableUserFields
{
    /**
     * The flag to determine whether a user is verified.
     *
     * @var bool
     */
    public bool $verified = false;
}
