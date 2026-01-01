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

namespace Valkyrja\Notification\Factory;

use Override;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Notification\Data\Contract\NotifyContract;
use Valkyrja\Notification\Factory\Contract\FactoryContract;

class ContainerFactory implements FactoryContract
{
    /**
     * ContainerFactory constructor.
     *
     * @param ContainerContract $container The container
     */
    public function __construct(
        protected ContainerContract $container
    ) {
    }

    /**
     * @inheritDoc
     *
     * @param class-string<NotifyContract> $name The notification name
     * @param array<array-key, mixed>      $data [optional] The data to add to the notification
     *
     * @return NotifyContract
     */
    #[Override]
    public function createNotification(string $name, array $data = []): NotifyContract
    {
        return $this->container->get($name, $data);
    }
}
