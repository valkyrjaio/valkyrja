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

namespace Valkyrja\Tests\Classes\Container;

use Valkyrja\Container\Attribute\Alias;
use Valkyrja\Container\Contract\ServiceContract as Contract;
use Valkyrja\Container\Manager\Contract\ContainerContract;

/**
 * Testable Service class.
 *
 * @author Melech Mizrachi
 */
#[Alias(ServiceClass::class)]
class Service2Class implements Contract
{
    public function __construct(
        public ContainerContract $container,
    ) {
    }

    public static function make(ContainerContract $container, array $arguments = []): static
    {
        return new self($container);
    }

    public function getContainer(): ContainerContract
    {
        return $this->container;
    }
}
