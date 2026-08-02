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

use Valkyrja\Http\Message\File\UploadedFile;

/**
 * Class InvalidUploadedFileExceptionFixture.
 */
final class InvalidUploadedFileExceptionFixture extends UploadedFile
{
    public function __construct()
    {
        parent::__construct('test');

        $this->file   = null;
        $this->stream = null;
    }
}
