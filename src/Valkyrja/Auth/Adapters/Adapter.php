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

namespace Valkyrja\Auth\Adapters;

use Valkyrja\Auth\Adapter as Contract;
use Valkyrja\Auth\Auth;
use Valkyrja\Auth\Authenticator;
use Valkyrja\Auth\Registrator;
use Valkyrja\Auth\Authenticators\Authenticator as AuthenticatorClass;
use Valkyrja\Auth\Registrators\Registrator as RegistratorClass;
use Valkyrja\Auth\User;
use Valkyrja\Support\ClassHelpers;

/**
 * Class Adapter.
 *
 * @author Melech Mizrachi
 */
class Adapter implements Contract
{
    /**
     * The authenticator.
     *
     * @var Authenticator
     */
    protected Authenticator $authenticator;

    /**
     * The registrator.
     *
     * @var Registrator
     */
    protected Registrator $registrator;

    /**
     * Adapter constructor.
     *
     * @param Authenticator $authenticator
     * @param Registrator   $registrator
     */
    public function __construct(Authenticator $authenticator, Registrator $registrator)
    {
        $this->authenticator = $authenticator;
        $this->registrator   = $registrator;
    }

    /**
     * Make a new adapter.
     *
     * @param Auth $auth
     *
     * @return static
     */
    public static function make(Auth $auth): self
    {
        $orm = $auth->getOrm();

        return new static(
            new AuthenticatorClass($auth->getCrypt(), $orm),
            new RegistratorClass($orm)
        );
    }

    /**
     * Get the authenticator.
     *
     * @return Authenticator
     */
    public function getAuthenticator(): Authenticator
    {
        return $this->authenticator;
    }

    /**
     * Get the registrator.
     *
     * @return Registrator
     */
    public function getRegistrator(): Registrator
    {
        return $this->registrator;
    }
}
