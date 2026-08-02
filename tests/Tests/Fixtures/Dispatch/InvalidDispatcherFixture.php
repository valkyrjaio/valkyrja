<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Dispatch;

use Valkyrja\Application\Kernel\Contract\ApplicationContract;

/**
 * Invalid dispatcher class to test with.
 */
final class InvalidDispatcherFixture
{
    public const string|null TEST = null;

    public static string $staticProperty;

    public string $property;

    /**
     * The application.
     *
     * @var ApplicationContract
     */
    private ApplicationContract $app;

    /**
     * InvalidContainerClass constructor.
     *
     * @param ApplicationContract $application The application
     */
    public function __construct(ApplicationContract $application)
    {
        $this->app = $application;
    }

    public static function staticMethod(): void
    {
    }

    public function method(): void
    {
    }
}
