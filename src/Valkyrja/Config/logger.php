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
 * Logger Configuration
 *-------------------------------------------------------------------------
 *
 * Logging is very helpful in understanding what occurs within your
 * application when its deployed and used by multiple users aside
 * from you and your developers. Configure that helpfulness here.
 *
 */
return [
    /*
     *-------------------------------------------------------------------------
     * Logger Log Name
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'name'     => env()::LOGGER_NAME ?? 'ApplicationLog',

    /*
     *-------------------------------------------------------------------------
     * Logger Log File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'filePath' => env()::LOGGER_FILE_PATH ?? Directory::storagePath('logs/valkyrja.log'),
];
