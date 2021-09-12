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

namespace Valkyrja\View\Facades;

use Valkyrja\Support\Facade\Facade;
use Valkyrja\View\Engine;
use Valkyrja\View\Template;
use Valkyrja\View\View as Contract;

/**
 * Class View.
 *
 * @author Melech Mizrachi
 *
 * @method static Template createTemplate(string $template = null, array $variables = [], string $engine = null)
 * @method static Engine getEngine(string $name = null)
 * @method static string render(string $name, array $variables = [])
 */
class View extends Facade
{
    /**
     * @inheritDoc
     */
    public static function instance()
    {
        return self::$container->getSingleton(Contract::class);
    }
}
