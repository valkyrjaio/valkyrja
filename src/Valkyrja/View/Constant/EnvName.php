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
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const string DEFAULT_CONFIGURATION = 'VIEW_DEFAULT_CONFIGURATION';
    public const string CONFIGURATIONS        = 'VIEW_CONFIGURATIONS';

    public const string PHP_ENGINE         = 'VIEW_PHP_ENGINE';
    public const string PHP_FILE_EXTENSION = 'VIEW_PHP_FILE_EXTENSION';
    public const string PHP_DIR            = 'VIEW_PHP_DIR';
    public const string PHP_PATHS          = 'VIEW_PHP_PATHS';

    public const string ORKA_ENGINE         = 'VIEW_ORKA_ENGINE';
    public const string ORKA_FILE_EXTENSION = 'VIEW_ORKA_FILE_EXTENSION';
    public const string ORKA_DIR            = 'VIEW_ORKA_DIR';
    public const string ORKA_PATHS          = 'VIEW_ORKA_PATHS';

    public const string TWIG_ENGINE         = 'VIEW_TWIG_ENGINE';
    public const string TWIG_FILE_EXTENSION = 'VIEW_TWIG_FILE_EXTENSION';
    public const string TWIG_DIR            = 'VIEW_TWIG_DIR';
    public const string TWIG_PATHS          = 'VIEW_TWIG_PATHS';
    public const string TWIG_EXTENSIONS     = 'VIEW_TWIG_EXTENSIONS';
    public const string TWIG_COMPILED_DIR   = 'VIEW_TWIG_COMPILED_DIR';
}
