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

namespace Valkyrja\Validation\Factories;

use Valkyrja\Container\Contract\Container;
use Valkyrja\Validation\Factory;

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
     * @param Container $container The container service
     */
    public function __construct(
        protected Container $container,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function createRules(string $name): object
    {
        return $this->container->get($name);
    }
}
