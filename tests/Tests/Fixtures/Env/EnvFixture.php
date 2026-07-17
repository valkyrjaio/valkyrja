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
 * Class Env.
 */
final class EnvFixture extends Env
{
    /** @var string|array{0: class-string, 1: string} */
    public const array|string DATA_CONFIG_PUBLIC = 'publicFromEnv';
    /** @var string|array{0: class-string, 1: string} */
    public const array|string DATA_CONFIG_NULLABLE = 'nullableFromEnv';
}
