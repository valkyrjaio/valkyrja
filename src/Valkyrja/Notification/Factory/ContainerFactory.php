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

use Valkyrja\Container\Contract\Container;
use Valkyrja\Notification\Data\Contract\Notify;
use Valkyrja\Notification\Factory\Contract\Factory;

/**
 * Class ContainerFactory.
 *
 * @author Melech Mizrachi
 */
class ContainerFactory implements Factory
{
    /**
     * ContainerFactory constructor.
     *
     * @param Container $container The container
     */
    public function __construct(
        protected Container $container
    ) {
    }

    /**
     * @inheritDoc
     */
    public function createNotification(string $name, array $data = []): Notify
    {
        return $this->container->get(
            $this->config['notifications'][$name] ?? $name,
            $data
        );
    }
}
