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

use Valkyrja\ORM\ORM;

/**
 * Abstract Class Migration.
 *
 * @author Melech Mizrachi
 */
abstract class Migration
{
    /**
     * Run the migration.
     *
     * @param ORM $orm The ORM
     *
     * @return void
     */
    abstract public static function run(ORM $orm): void;

    /**
     * Rollback the migration.
     *
     * @param ORM $orm The ORM
     *
     * @return void
     */
    abstract public static function rollback(ORM $orm): void;
}
