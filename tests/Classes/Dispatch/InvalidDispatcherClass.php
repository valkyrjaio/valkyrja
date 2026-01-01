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

namespace Valkyrja\Tests\Classes\Dispatch;

use Valkyrja\Application\Kernel\Contract\ApplicationContract;

/**
 * Invalid dispatcher class to test with.
 *
 * @author Melech Mizrachi
 */
class InvalidDispatcherClass
{
    public const string|null TEST = null;

    public static string $staticProperty;

    public string $property;

    /**
     * The application.
     *
     * @var ApplicationContract
     */
    protected ApplicationContract $app;

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
