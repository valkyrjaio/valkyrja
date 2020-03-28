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

namespace Valkyrja\Auth;

/**
 * Interface Adapter.
 *
 * @author Melech Mizrachi
 */
interface Adapter
{
    /**
     * Make a new adapter.
     *
     * @param Auth $auth
     *
     * @return static
     */
    public static function make(Auth $auth): self;

    /**
     * Get the authenticator.
     *
     * @return Authenticator
     */
    public function getAuthenticator(): Authenticator;

    /**
     * Get the registrator.
     *
     * @return Registrator
     */
    public function getRegistrator(): Registrator;
}
