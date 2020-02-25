<?php

declare(strict_types=1);

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
 *
 */

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Filesystem\FlysystemLocal;
use Valkyrja\Filesystem\FlysystemS3;

return [
    /*
     *-------------------------------------------------------------------------
     * Filesystem Default Disk
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::DEFAULT  => env(EnvKey::FILESYSTEM_DEFAULT, CKP::LOCAL),

    /*
     *-------------------------------------------------------------------------
     * Filesystem Adapters
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::ADAPTERS => env(
        EnvKey::FILESYSTEM_ADAPTERS,
        [
            CKP::LOCAL => FlysystemLocal::class,
            CKP::S3    => FlysystemS3::class,
        ]
    ),

    /*
     *-------------------------------------------------------------------------
     * Filesystem Disks
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::DISKS    => [
        CKP::LOCAL => [
            CKP::DIR     => env(EnvKey::FILESYSTEM_LOCAL_DIR, storagePath('app')),
            CKP::ADAPTER => env(EnvKey::FILESYSTEM_LOCAL_ADAPTER, CKP::LOCAL),
        ],

        CKP::S3 => [
            CKP::KEY     => env(EnvKey::FILESYSTEM_S3_KEY),
            CKP::SECRET  => env(EnvKey::FILESYSTEM_S3_SECRET),
            CKP::REGION  => env(EnvKey::FILESYSTEM_S3_REGION),
            CKP::VERSION => env(EnvKey::FILESYSTEM_S3_VERSION),
            CKP::BUCKET  => env(EnvKey::FILESYSTEM_S3_BUCKET),
            CKP::DIR     => env(EnvKey::FILESYSTEM_S3_DIR, '/'),
            CKP::OPTIONS => env(EnvKey::FILESYSTEM_S3_OPTIONS, []),
            CKP::ADAPTER => env(EnvKey::FILESYSTEM_S3_ADAPTER, CKP::S3),
        ],
    ],
];
