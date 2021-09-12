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

use Valkyrja\Path\PathParser as Contract;
use Valkyrja\Support\Facade\Facade;

/**
 * Class PathParser.
 *
 * @author Melech Mizrachi
 *
 * @method static array parse(string $path)
 */
class PathParser extends Facade
{
    /**
     * @inheritDoc
     */
    public static function instance()
    {
        return self::$container->getSingleton(Contract::class);
    }
}
