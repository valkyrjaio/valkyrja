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

namespace Valkyrja\ORM\Migrations;

use Valkyrja\ORM\Migration as Contract;
use Valkyrja\ORM\ORM;

/**
 * Abstract Class Migration.
 *
 * @author Melech Mizrachi
 */
abstract class Migration implements Contract
{
    /**
     * The ORM service.
     *
     * @var ORM
     */
    protected ORM $orm;

    /**
     * Migration constructor.
     *
     * @param ORM $orm The ORM service
     */
    public function __construct(ORM $orm)
    {
        $this->orm = $orm;
    }

    /**
     * @inheritDoc
     */
    abstract public function run(): void;

    /**
     * @inheritDoc
     */
    abstract public function rollback(): void;
}