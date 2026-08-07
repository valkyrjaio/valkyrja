<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Abstract;

use Override;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\PhpUnit\Abstract\ValkyrjaTestCase;

use function fopen;
use function is_dir;
use function ob_get_clean;
use function scandir;
use function unlink;

/**
 * Test case for tests.
 */
abstract class TestCase extends ValkyrjaTestCase
{
    /**
     * Open a stream for a test.
     *
     * @return resource
     */
    protected static function openStream(string $filename, string $mode)
    {
        $stream = fopen($filename, $mode);

        if ($stream === false) {
            self::fail("Unable to open the $filename stream.");
        }

        return $stream;
    }

    /**
     * Get and delete the current output buffer.
     */
    protected static function cleanOutputBuffer(): string
    {
        $contents = ob_get_clean();

        return $contents === false
            ? ''
            : $contents;
    }

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
