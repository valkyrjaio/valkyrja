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

namespace Valkyrja\Tests\Classes\Application\Entry;

use Override;
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Entry\Abstract\App;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Kernel\Valkyrja;
use Valkyrja\Container\Manager\Container;

abstract class AppExceptionHandlerClass extends App
{
    public static bool $called = false;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function appStart(): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function directory(string $dir): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function app(Env $env, Config $config): ApplicationContract
    {
        return new Valkyrja(new Container(), $config);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected static function defaultExceptionHandler(): void
    {
        self::$called = true;
    }
}
