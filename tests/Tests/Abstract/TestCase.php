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

namespace Valkyrja\Tests\Abstract;

use Override;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\PhpUnit\Abstract\ValkyrjaTestCase;

/**
 * Test case for tests.
 */
abstract class TestCase extends ValkyrjaTestCase
{
    /**
     * @inheritDoc
     */
    #[Override]
    protected function tearDown(): void
    {
        $dir = Directory::storagePath();

        /** @var string[] $files */
        $files = scandir($dir);

        foreach ($files as $file) {
            $filepath = $dir . '/' . $file;

            if ($file !== '.gitignore' && ! is_dir($filepath)) {
                @unlink($filepath);
            }
        }
    }
}
