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

namespace Valkyrja\Support\Generator;

use Override;
use Throwable;
use Valkyrja\Support\Generator\Contract\FileGeneratorContract;
use Valkyrja\Support\Generator\Enum\GenerateStatus;

abstract class FileGenerator implements FileGeneratorContract
{
    /**
     * @param non-empty-string $filePath The file path
     */
    public function __construct(
        protected string $filePath
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function generateFile(): GenerateStatus
    {
        try {
            $results = $this->filePutContents();

            if ($results === false) {
                return GenerateStatus::FAILURE;
            }
        } catch (Throwable) {
            return GenerateStatus::FAILURE;
        }

        return GenerateStatus::SUCCESS;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    abstract public function generateFileContents(): string;

    /**
     * Wrapper for the file_put_contents function.
     */
    protected function filePutContents(): int|false
    {
        return file_put_contents(filename: $this->filePath, data: $this->generateFileContents());
    }
}
