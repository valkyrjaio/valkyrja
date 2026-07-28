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

namespace Valkyrja\Tests\Fixtures\Env;

use Valkyrja\Application\Env\Env;

/**
 * Env with the optional MySQL strict mode and storage engine set, which default to null.
 */
final class OrmMysqlStrictEngineEnvFixture extends Env
{
    /** @var bool|null */
    public const bool|null ORM_MYSQL_STRICT = true;
    /** @var non-empty-string|null */
    public const string|null ORM_MYSQL_ENGINE = 'InnoDB';
}
