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

trait UserFields
{
    /**
     * The id.
     *
     * @var non-empty-string
     */
    public string $id;

    /**
     * The username.
     *
     * @var non-empty-string
     */
    public string $username;

    /**
     * The password.
     *
     * @var non-empty-string
     */
    protected string $password;

    /**
     * The password reset token.
     *
     * @var non-empty-string|null
     */
    protected string|null $reset_token = null;
}
