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

namespace Valkyrja\Application\Provider\Contract;

use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Cli\Routing\Provider\Contract\ProviderContract as CliProvider;
use Valkyrja\Container\Provider\Contract\ProviderContract as ContainerProvider;
use Valkyrja\Event\Provider\Contract\ProviderContract as EventProvider;
use Valkyrja\Http\Routing\Provider\Contract\ProviderContract as HttpProvider;

interface ProviderContract
{
    /**
     * Get the component's container service providers.
     *
     * @return class-string<ContainerProvider>[]
     */
    public static function getContainerProviders(ApplicationContract $app): array;

    /**
     * Get the component's event listener providers.
     *
     * @return class-string<EventProvider>[]
     */
    public static function getEventProviders(ApplicationContract $app): array;

    /**
     * Get the component's cli route providers.
     *
     * @return class-string<CliProvider>[]
     */
    public static function getCliProviders(ApplicationContract $app): array;

    /**
     * Get the component's http route providers.
     *
     * @return class-string<HttpProvider>[]
     */
    public static function getHttpProviders(ApplicationContract $app): array;
}
