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

use Valkyrja\Facade\ContainerFacade;
use Valkyrja\Path\PathGenerator as Contract;

/**
 * Class PathGenerator.
 *
 * @author Melech Mizrachi
 *
 * @method static string parse(array $segments, array $data = null, array $params = null)
 */
class PathGenerator extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object|string
    {
        return self::getContainer()->getSingleton(Contract::class);
    }
}
