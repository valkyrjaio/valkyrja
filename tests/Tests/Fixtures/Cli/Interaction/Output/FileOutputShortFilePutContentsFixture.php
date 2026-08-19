<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Cli\Interaction\Output;

use Override;
use Valkyrja\Cli\Interaction\Output\FileOutput;

use function error_clear_last;

/**
 * Testable FileOutput class that stores part of the data.
 */
final class FileOutputShortFilePutContentsFixture extends FileOutput
{
    #[Override]
    protected function filePutContents(string $filepath, string $data): int|false
    {
        error_clear_last();

        return 1;
    }
}
