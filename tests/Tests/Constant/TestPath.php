<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Constant;

final class TestPath
{
    /**
     * The tests directory.
     *
     * A test that reads a template or writes to storage resolves its path from
     * here.
     *
     * @var non-empty-string
     */
    public const string APP_DIR = __DIR__ . '/../../';
}
