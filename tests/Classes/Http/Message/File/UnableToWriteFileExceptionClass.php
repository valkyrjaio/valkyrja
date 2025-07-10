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

use Override;
use Valkyrja\Http\Message\File\UploadedFile;

/**
 * Class UnableToWriteFileExceptionClass.
 *
 * @author Melech Mizrachi
 */
class UnableToWriteFileExceptionClass extends UploadedFile
{
    /**
     * @inheritDoc
     */
    #[Override]
    protected function openStream(string $filename)
    {
        return false;
    }
}
