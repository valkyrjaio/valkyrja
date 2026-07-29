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

namespace Valkyrja\Tests\Fixtures\Http\Message\File;

use Override;
use Valkyrja\Http\Message\File\Factory\UploadedFileFactory;

/**
 * Drives the SAPI seam of UploadedFileFactory, so tests can exercise
 * isValidSapiEnvironmentForUploads() for SAPIs other than the one PHPUnit
 * itself runs under.
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
