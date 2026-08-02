<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Session\Manager;

use Override;
use Valkyrja\Session\Manager\Abstract\Session;

class NullSession extends Session
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function start(): void
    {
    }
}
