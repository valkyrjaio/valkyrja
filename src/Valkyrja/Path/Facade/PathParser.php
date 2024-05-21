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

namespace Valkyrja\Path\Facade;

use Valkyrja\Facade\ContainerFacade;
use Valkyrja\Path\Parser\Contract\Parser as Contract;

/**
 * Class PathParser.
 *
 * @author Melech Mizrachi
 *
 * @method static array parse(string $path)
 */
class PathParser extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object
    {
        return self::getContainer()->getSingleton(Contract::class);
    }
}
