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

namespace Valkyrja\ORM\Support;

use Exception;
use Valkyrja\ORM\ORM;

/**
 * Abstract Class SmartMigration.
 *
 * @author Melech Mizrachi
 */
abstract class SmartMigration extends Migration
{
    /**
     * Run the migration.
     *
     * @param ORM $orm The ORM
     *
     * @return void
     */
    public static function run(ORM $orm): void
    {
        try {
            $orm->ensureTransaction();

            static::runMigration($orm);

            $orm->persist();
        } catch (Exception $exception) {
            $orm->rollback();

            static::runFailure($orm, $exception);

            throw $exception;
        }
    }

    /**
     * Rollback the migration.
     *
     * @param ORM $orm The ORM
     *
     * @return void
     */
    public static function rollback(ORM $orm): void
    {
        try {
            $orm->ensureTransaction();

            static::rollbackMigration($orm);

            $orm->persist();
        } catch (Exception $exception) {
            $orm->rollback();

            static::rollbackFailure($orm, $exception);

            throw $exception;
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
     * Do on rollback failure.
     *
     * @param ORM       $orm       The ORM
     * @param Exception $exception The exception
     *
     * @return void
     */
    protected static function rollbackFailure(ORM $orm, Exception $exception): void
    {
    }

    /**
     * Run the migration.
     *
     * @param ORM $orm The ORM
     *
     * @return void
     */
    abstract protected static function runMigration(ORM $orm): void;

    /**
     * Rollback the migration.
     *
     * @param ORM $orm The ORM
     *
     * @return void
     */
    abstract protected static function rollbackMigration(ORM $orm): void;
}
