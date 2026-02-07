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
 * Class MoveUploadedFileClass.
 */
class MoveUploadedFileClass extends UploadedFile
{
    /**
     * @inheritDoc
     */
    #[Override]
    protected function shouldWriteStream(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function moveUploadedFile(string $from, string $to): bool
    {
        // Simulate the results of move_uploaded_file
        $this->writeStream($to);
        $this->deleteFile($from);

        return true;
    }
}
