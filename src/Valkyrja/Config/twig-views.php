<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Valkyrja\Support\Directory;

/*
 *-------------------------------------------------------------------------
 * Twig Views Configuration
 *-------------------------------------------------------------------------
 *
 * //
 *
 */
return [
    /*
     *-------------------------------------------------------------------------
     * Twig Views Directories
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'dirs'        => env()::VIEWS_TWIG_DIRS ?? [
            env()::VIEWS_TWIG_DIR_NS ?? Twig_Loader_Filesystem::MAIN_NAMESPACE => env()::VIEWS_TWIG_DIR ?? Directory::resourcesPath('views/twig'),
        ],

    /*
     *-------------------------------------------------------------------------
     * Twig Views Compiled Directory Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'compiledDir' => env()::VIEWS_TWIG_COMPILED_DIR ?? Directory::storagePath('views/twig'),

    /*
     *-------------------------------------------------------------------------
     * Twig Views Extensions
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'extensions'  => env()::VIEWS_TWIG_EXTENSIONS ?? [],
];
