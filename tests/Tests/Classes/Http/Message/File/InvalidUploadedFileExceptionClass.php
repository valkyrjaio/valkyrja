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

namespace Valkyrja\Tests\Classes\Http\Message\File;

use Valkyrja\Http\Message\File\UploadedFile;

/**
 * Class InvalidUploadedFileExceptionClass.
 */
final class InvalidUploadedFileExceptionClass extends UploadedFile
{
    public function __construct()
    {
        parent::__construct('test');

        $this->file   = null;
        $this->stream = null;
    }
}
