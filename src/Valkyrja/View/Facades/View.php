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
use Valkyrja\View\View as Contract;

/**
 * Class View.
 *
 * @author Melech Mizrachi
 *
 * @method static Contract make(string $template = null, array $variables = [])
 * @method static Engine getEngine(string $name = null)
 * @method static array getVariables()
 * @method static Contract setVariables(array $variables = [])
 * @method static mixed variable(string $key)
 * @method static Contract setVariable(string $key, $value)
 * @method static string escape(string $value)
 * @method static string getTemplateDir(string $path = null)
 * @method static Contract setTemplateDir(string $path)
 * @method static string getFileExtension()
 * @method static Contract setFileExtension(string $extension)
 * @method static string getLayoutPath()
 * @method static string getTemplatePath()
 * @method static Contract setLayout(string $layout = null)
 * @method static Contract withoutLayout()
 * @method static Contract setTemplate(string $template)
 * @method static string getPartial(string $partial, array $variables = [])
 * @method static string getBlock(string $name)
 * @method static bool hasBlock(string $name)
 * @method static bool hasBlockEnded(string $name)
 * @method static void startBlock(string $name)
 * @method static string endBlock(string $name)
 * @method static string render(array $variables = [])
 * @method static string __toString()
 */
class View extends Facade
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
