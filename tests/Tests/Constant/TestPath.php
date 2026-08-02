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
