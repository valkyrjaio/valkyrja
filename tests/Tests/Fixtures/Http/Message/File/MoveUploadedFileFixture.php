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
 * Class MoveUploadedFileFixture.
 */
final class MoveUploadedFileFixture extends UploadedFile
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
