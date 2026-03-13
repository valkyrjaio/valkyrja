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

namespace Valkyrja\Support\Generator\Abstract;

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
            $data     = $this->generateFileContents();
            $existing = $this->fileGetContents();

            if ($existing === $data) {
                return GenerateStatus::SKIPPED;
            }

            $results = $this->filePutContents(data: $data);

            if ($results !== false) {
                return GenerateStatus::SUCCESS;
            }
        } catch (Throwable) {
            // Fallthrough
        }

        return GenerateStatus::FAILURE;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    abstract public function generateFileContents(): string;

    /**
     * Wrapper for the file_get_contents function.
     */
    protected function fileGetContents(): string|false
    {
        if (! is_file(filename: $this->filePath)) {
            return false;
        }

        return file_get_contents(filename: $this->filePath);
    }

    /**
     * Wrapper for the file_put_contents function.
     */
    protected function filePutContents(string $data): int|false
    {
        return file_put_contents(filename: $this->filePath, data: $data);
    }

    /**
     * Generate the contents of an object or array of objects.
     *
     * @param object|array<object> $subject The object or array of objects
     *
     * @return non-empty-string
     */
    protected function generateObjectsContents(object|array $subject): string
    {
        $contents = var_export($subject, true);
        $contents = preg_replace('/([.^\S]*)::__set_state\(/', 'new $1(...', $contents);

        /** @var non-empty-string $contents */

        return $contents;
    }
}
