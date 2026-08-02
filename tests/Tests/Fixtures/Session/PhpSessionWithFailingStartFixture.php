<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Session;

use Override;
use Valkyrja\Session\Manager\PhpSession;

/**
 * Test class that simulates session_start() failure.
 */
final class PhpSessionWithFailingStartFixture extends PhpSession
{
    /**
     * @inheritDoc
     */
    #[Override]
    protected function sessionStart(): bool
    {
        return false;
    }
}
