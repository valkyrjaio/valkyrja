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

namespace Valkyrja\ORM\Factories;

use Valkyrja\Container\Container;
use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\Driver;
use Valkyrja\ORM\DriverFactory as Contract;
use Valkyrja\Support\Type\Cls;

/**
 * Class DriverFactory.
 *
 * @author Melech Mizrachi
 */
class DriverFactory implements Contract
{
    /**
     * The container service.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * AdapterFactory constructor.
     *
     * @param Container $container The container service
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function createDriver(Adapter $adapter, string $name, array $config): Driver
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            Driver::class,
            [
                $adapter,
                $config,
            ]
        );
    }
}
