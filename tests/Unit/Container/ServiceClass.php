<?php
declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Container;

use Valkyrja\Container\Container;
use Valkyrja\Container\Service;

/**
 * Testable Service class.
 *
 * @author Melech Mizrachi
 */
class ServiceClass implements Service
{
    public static function make(Container $container, array $arguments = []): Service
    {
        return new self();
    }
}
