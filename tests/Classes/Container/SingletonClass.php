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

use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Contract\Service;

/**
 * Testable Singleton class.
 *
 * @author Melech Mizrachi
 */
class SingletonClass implements Service
{
    public static function make(Container $container, array $arguments = []): static
    {
        return new self();
    }
}
