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

namespace Valkyrja\Tests\Unit\Support\Generator;

use Override;
use RuntimeException;
use Valkyrja\Support\Directory\Directory;
use Valkyrja\Support\Generator\Enum\GenerateStatus;
use Valkyrja\Support\Generator\FileGenerator;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class FileGeneratorTest extends TestCase
{
    public function testGenerateFile(): void
    {
        Directory::$BASE_PATH = EnvClass::APP_DIR;

        $filePath  = Directory::cachePath('FileGeneratorTest.testGenerateFile.php');
        $generator = new class($filePath) extends FileGenerator {
            #[Override]
            public function generateFileContents(): string
            {
                return FileGeneratorTest::class . 'testGenerateFile contents';
            }
        };
        $results   = $generator->generateFile();

        self::assertSame(GenerateStatus::SUCCESS, $results);
        self::assertSame($generator->generateFileContents(), @file_get_contents($filePath));

        @unlink($filePath);
    }

    public function testGenerateFileFailure(): void
    {
        $generator = new class('filepath.php') extends FileGenerator {
            #[Override]
            protected function filePutContents(): int|false
            {
                return false;
            }

            #[Override]
            public function generateFileContents(): string
            {
                return '';
            }
        };
        $results   = $generator->generateFile();

        self::assertSame(GenerateStatus::FAILURE, $results);
    }

    public function testGenerateFileFailureDueToException(): void
    {
        $generator = new class('filepath.php') extends FileGenerator {
            #[Override]
            protected function filePutContents(): int|false
            {
                throw new RuntimeException('Exception to test with');
            }

            #[Override]
            public function generateFileContents(): string
            {
                return '';
            }
        };
        $results   = $generator->generateFile();

        self::assertSame(GenerateStatus::FAILURE, $results);
    }
}
