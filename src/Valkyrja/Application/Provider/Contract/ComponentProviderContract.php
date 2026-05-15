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
use Valkyrja\Cli\Routing\Provider\Contract\CliRouteProviderContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Event\Provider\Contract\ListenerProviderContract;
use Valkyrja\Http\Routing\Provider\Contract\HttpRouteProviderContract;

interface ComponentProviderContract
{
    /**
     * Get the component providers this component depends on.
     *
     * @return ComponentProviderContract[]
     */
    public function getComponentProviders(ApplicationContract $app): array;

    /**
     * Get the component's container service providers.
     *
     * @return ServiceProviderContract[]
     */
    public function getContainerProviders(ApplicationContract $app): array;

    /**
     * Get the component's event listener providers.
     *
     * @return ListenerProviderContract[]
     */
    public function getEventProviders(ApplicationContract $app): array;

    /**
     * Get the component's cli route providers.
     *
     * @return CliRouteProviderContract[]
     */
    public function getCliProviders(ApplicationContract $app): array;

    /**
     * Get the component's http route providers.
     *
     * @return HttpRouteProviderContract[]
     */
    public function getHttpProviders(ApplicationContract $app): array;
}
