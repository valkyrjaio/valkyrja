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
use Valkyrja\Http\Message\File\UploadedFile;

/**
 * Class InvalidDirectoryExceptionFixture.
 */
final class InvalidDirectoryExceptionFixture extends UploadedFile
{
    /**
     * @inheritDoc
     */
    #[Override]
    protected function isDir(string $filename): bool
    {
        return false;
    }
}
