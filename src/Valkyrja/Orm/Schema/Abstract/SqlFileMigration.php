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

namespace Valkyrja\Orm\Schema\Abstract;

use Override;
use Throwable;
use Valkyrja\Orm\Throwable\Exception\RuntimeException;

use function explode;
use function file_get_contents;
use function trim;

abstract class SqlFileMigration extends TransactionalMigration
{
    /**
     * @inheritDoc
     */
    #[Override]
    protected function runMigration(): void
    {
        $this->executeSql($this->getRunMigrationFilePath());
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function rollbackMigration(): void
    {
        $this->executeSql($this->getRollbackMigrationFilePath());
    }

    /**
     * Execute sql.
     *
     * @param string $filePath The sql file path
     */
    protected function executeSql(string $filePath): void
    {
        $sql = file_get_contents($filePath);

        if ($sql === false) {
            throw new RuntimeException("Invalid file $filePath given");
        }

        foreach (explode(';', trim($sql)) as $queryString) {
            if (! $queryString) {
                continue;
            }

            $statement = $this->orm->prepare($queryString);

            if (! $statement->execute()) {
                throw new RuntimeException($statement->errorMessage() ?? 'Error occurred');
            }
        }
    }

    /**
     * Do on run failure.
     *
     * @param Throwable $exception The exception
     */
    #[Override]
    protected function runFailure(Throwable $exception): void
    {
    }

    /**
     * Get the run sql file path.
     */
    abstract protected function getRunMigrationFilePath(): string;

    /**
     * Get the rollback sql file path.
     */
    abstract protected function getRollbackMigrationFilePath(): string;
}
