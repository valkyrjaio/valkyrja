<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Http\Message\File;

use Override;
use Valkyrja\Http\Message\File\Factory\UploadedFileFactory;

/**
 * Drives UploadedFileFactory's SAPI seam for SAPIs other than the one PHPUnit runs under.
 */
final class UploadedFileFactorySapiFixture extends UploadedFileFactory
{
    /** The SAPI name to report to isValidSapiEnvironmentForUploads(). */
    public static string $sapi = 'cli';

    #[Override]
    protected static function getSapi(): string
    {
        return self::$sapi;
    }
}
