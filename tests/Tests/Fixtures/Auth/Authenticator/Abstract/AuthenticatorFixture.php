<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Auth\Authenticator\Abstract;

use Valkyrja\Auth\Authenticator\Abstract\Authenticator;
use Valkyrja\Auth\Entity\Contract\UserContract;

/**
 * Concrete implementation of Authenticator for testing.
 *
 * @extends Authenticator<UserContract>
 */
final class AuthenticatorFixture extends Authenticator
{
}
