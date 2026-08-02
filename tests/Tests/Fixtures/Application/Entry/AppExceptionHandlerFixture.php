<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Application\Entry;

use Override;
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Application\Entry\Abstract\App;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Kernel\Valkyrja;
use Valkyrja\Container\Manager\Container;

abstract class AppExceptionHandlerFixture extends App
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
    public static function app(ConfigContract $config): ApplicationContract
    {
        return new Valkyrja(new Container(), $config);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function defaultExceptionHandler(): void
    {
        self::$called = true;
    }
}
