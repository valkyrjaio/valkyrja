<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 *-------------------------------------------------------------------------
 * Filesystem Configuration
 *-------------------------------------------------------------------------
 *
 * How the application stores, retrieves, copies, and manipulates files
 * across the filesystem it is located within is a necessity in most
 * applications. Configure that manipulative module here.
     */
return [
    /*
     *-------------------------------------------------------------------------
     * Filesystem Default Adapter
     *-------------------------------------------------------------------------
     *
     * //
     */
    'default'  => env('FILESYSTEM_DEFAULT', 'local'),

    /*
     *-------------------------------------------------------------------------
     * Filesystem Adapters
     *-------------------------------------------------------------------------
     *
     * //
     */
    'adapters' => [
        'local' => [
            'dir' => env('FILESYSTEM_LOCAL_DIR', storagePath('app')),
        ],

        's3' => [
            'key'     => env('FILESYSTEM_S3_KEY'),
            'secret'  => env('FILESYSTEM_S3_SECRET'),
            'region'  => env('FILESYSTEM_S3_REGION'),
            'version' => env('FILESYSTEM_S3_VERSION'),
            'bucket'  => env('FILESYSTEM_S3_BUCKET'),
            'dir'     => env('FILESYSTEM_S3_DIR', '/'),
            'options' => env('FILESYSTEM_S3_OPTIONS', []),
        ],
    ],
];
