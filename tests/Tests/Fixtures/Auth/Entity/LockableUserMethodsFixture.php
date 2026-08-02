<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Auth\Entity;

use Valkyrja\Auth\Entity\Trait\LockableUserMethods;

/**
 * The smallest carrier for the LockableUserMethods trait.
 */
final class LockableUserMethodsFixture
{
    use LockableUserMethods;
}
