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

namespace Valkyrja\Auth\Entities;

/**
 * Trait UserFields.
 *
 * @author Melech Mizrachi
 */
trait UserFields
{
    /**
     * The username.
     *
     * @var string
     */
    public string $username;

    /**
     * The password.
     *
     * @var string
     */
    protected string $password;

    /**
     * The password reset token.
     *
     * @var string|null
     */
    protected ?string $reset_token = null;
}
