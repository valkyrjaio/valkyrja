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

namespace Valkyrja\Auth\Entity;

/**
 * Trait UserFields.
 *
 * @author Melech Mizrachi
 */
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
