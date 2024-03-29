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

use Valkyrja\Container\Container;
use Valkyrja\Container\Service as Contract;

/**
 * Testable Service class.
 *
 * @author Melech Mizrachi
 */
class Service implements Contract
{
    public static function make(Container $container, array $arguments = []): static
    {
        return new self();
    }
}
