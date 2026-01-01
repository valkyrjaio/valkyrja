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

use Valkyrja\Container\Contract\ServiceContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;

/**
 * Testable Singleton class.
 *
 * @author Melech Mizrachi
 */
class SingletonClass implements ServiceContract
{
    public static function make(ContainerContract $container, array $arguments = []): static
    {
        return new self();
    }
}
