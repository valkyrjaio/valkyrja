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
     * Pass a value that is deliberately outside a parameter's declared type.
     *
     * A guard that rejects bad input can only be reached with input the declared
     * type forbids, and the declared type is worth keeping for every honest caller.
     * Funnel the bad value through here so the intent is explicit and greppable at
     * the call site, rather than suppressed.
     */
    protected static function invalidInput(mixed $value): mixed
    {
        return $value;
    }

    /**
     * Open a stream for a test.
     *
     * `fopen()` is typed `resource|false`, and every caller here opens a stream it
     * expects to exist; fail the test outright rather than hand each one a `false`
     * it has no meaningful way to handle.
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
     *
     * `ob_get_clean()` is typed `string|false` because it fails when no buffer is
     * active; every caller here opens one first, so normalize the type away rather
     * than have each assertion carry a `false` it can never receive.
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
