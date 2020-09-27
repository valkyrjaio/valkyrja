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

            static::runFailure($orm);
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

            static::rollbackFailure($orm);
        }
    }

    /**
     * Do on run failure.
     *
     * @param ORM $orm The ORM
     *
     * @return void
     */
    public static function runFailure(ORM $orm): void
    {
    }

    /**
     * Do on rollback failure.
     *
     * @param ORM $orm The ORM
     *
     * @return void
     */
    public static function rollbackFailure(ORM $orm): void
    {
    }

    /**
     * Run the migration.
     *
     * @param ORM $orm The ORM
     *
     * @return void
     */
    abstract public static function runMigration(ORM $orm): void;

    /**
     * Rollback the migration.
     *
     * @param ORM $orm The ORM
     *
     * @return void
     */
    abstract public static function rollbackMigration(ORM $orm): void;
}
