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

trait LockableUserFields
{
    /**
     * The number of login attempts.
     *
     * @var int
     */
    public int $login_attempts = 0;

    /**
     * The flag to determine whether a user is locked.
     *
     * @var bool
     */
    public bool $locked = false;
}
