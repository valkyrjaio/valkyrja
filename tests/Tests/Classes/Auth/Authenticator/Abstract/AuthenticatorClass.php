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

namespace Valkyrja\Tests\Classes\Auth\Authenticator\Abstract;

use Valkyrja\Auth\Authenticator\Abstract\Authenticator;
use Valkyrja\Auth\Entity\Contract\UserContract;

/**
 * Concrete implementation of Authenticator for testing.
 *
 * @extends Authenticator<UserContract>
 */
class AuthenticatorClass extends Authenticator
{
}
