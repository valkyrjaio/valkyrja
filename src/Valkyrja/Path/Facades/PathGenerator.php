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

namespace Valkyrja\Path\Facades;

use Valkyrja\Path\PathGenerator as Contract;
use Valkyrja\Support\Facade\Facade;

/**
 * Class PathGenerator.
 *
 * @author Melech Mizrachi
 *
 * @method static string parse(array $segments, array $data = null, array $params = null)
 */
class PathGenerator extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return self::$container->getSingleton(Contract::class);
    }
}
