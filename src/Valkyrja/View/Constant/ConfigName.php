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

namespace Valkyrja\View\Constant;

/**
 * Class ConfigName.
 *
 * @author Melech Mizrachi
 */
final class ConfigName
{
    public const string DEFAULT_CONFIGURATION = 'defaultConfiguration';
    public const string CONFIGURATIONS        = 'configurations';

    public const string DIR            = 'dir';
    public const string PATH           = 'path';
    public const string ENGINE         = 'engine';
    public const string FILE_EXTENSION = 'fileExtension';
    public const string PATHS          = 'paths';
    public const string EXTENSIONS     = 'extensions';
    public const string COMPILED_DIR   = 'compiledDir';
}
