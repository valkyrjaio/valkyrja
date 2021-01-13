<?php

declare(strict_types=1);

/*
 * This file is part of the Lionadi Web Application package.
 *
 * (c) Lionadi, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM\Support;

use Exception;
use RuntimeException;
use Valkyrja\ORM\ORM;

use function explode;
use function file_get_contents;
use function trim;

/**
 * Class SqlFileMigration.
 */
abstract class SqlFileMigration extends SmartMigration
{
    /**
     * Run the migration.
     *
     * @param ORM $orm The ORM
     *
     * @return void
     */
    protected static function runMigration(ORM $orm): void
    {
        static::executeSql($orm, static::getRunMigrationFilePath());
    }

    /**
     * Rollback the migration.
     *
     * @param ORM $orm The ORM
     *
     * @return void
     */
    protected static function rollbackMigration(ORM $orm): void
    {
        static::executeSql($orm, static::getRollbackMigrationFilePath());
    }

    /**
     * Execute sql.
     *
     * @param ORM    $orm         The ORM
     * @param string $sqlFilePath The sql file path
     *
     * @return void
     */
    protected static function executeSql(ORM $orm, string $sqlFilePath): void
    {
        $sql = file_get_contents($sqlFilePath);

        foreach (explode(';', trim($sql)) as $queryString) {
            if (! $queryString) {
                continue;
            }

            $query = $orm->createQuery($queryString);

            if (! $query->execute()) {
                throw new RuntimeException($query->getError());
            }
        }
    }

    /**
     * Do on run failure.
     *
     * @param ORM       $orm       The ORM
     * @param Exception $exception The exception
     *
     * @return void
     */
    protected static function runFailure(ORM $orm, Exception $exception): void
    {
    }

    /**
     * Get the run sql file path.
     *
     * @return string
     */
    abstract protected static function getRunMigrationFilePath(): string;

    /**
     * Get the rollback sql file path.
     *
     * @return string
     */
    abstract protected static function getRollbackMigrationFilePath(): string;
}
