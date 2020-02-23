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

namespace Valkyrja\Http\Uris;

/**
 * Trait UserInfo.
 *
 * @author Melech Mizrachi
 *
 * @property string $userInfo
 */
trait UserInfo
{
    /**
     * Retrieve the user information component of the URI.
     * If no user information is present, this method MUST return an empty
     * string.
     * If a user is present in the URI, this will return that value;
     * additionally, if the password is also present, it will be appended to the
     * user value, with a colon (":") separating the values.
     * The trailing "@" character is not part of the user information and MUST
     * NOT be added.
     *
     * @return string The URI user information, in "username[:password]" format.
     */
    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    /**
     * Return an instance with the specified user information.
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified user information.
     * Password is optional, but the user information MUST include the
     * user; an empty string for the user is equivalent to removing user
     * information.
     *
     * @param string      $user     The user name to use for authority.
     * @param string|null $password The password associated with $user.
     *
     * @return static A new instance with the specified user information.
     */
    public function withUserInfo(string $user, string $password = null): self
    {
        $info = $user;

        if ($password) {
            $info .= ':' . $password;
        }

        if ($info === $this->userInfo) {
            return clone $this;
        }

        $new = clone $this;

        $new->userInfo = $info;

        return $new;
    }
}
