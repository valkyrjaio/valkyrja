<?php

declare(strict_types = 1);

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
 * Database Configuration
 *-------------------------------------------------------------------------
 *
 * Persist your application's data through a data store using a database
 * connection method. All configurations for getting you going with
 * a few different data stores is available here.
 *
 */

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;

return [
    /*
     *-------------------------------------------------------------------------
     * Default Database Connection Name
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::DEFAULT     => env(EnvKey::DB_CONNECTION, CKP::MYSQL),

    /*
     *-------------------------------------------------------------------------
     * Database Connections
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::CONNECTIONS => [

        CKP::MYSQL => [
            CKP::DRIVER      => CKP::MYSQL,
            CKP::HOST        => env(EnvKey::DB_HOST, '127.0.0.1'),
            CKP::PORT        => env(EnvKey::DB_PORT, '3306'),
            CKP::DB          => env(EnvKey::DB_DATABASE, 'forge'),
            CKP::USERNAME    => env(EnvKey::DB_USERNAME, 'forge'),
            CKP::PASSWORD    => env(EnvKey::DB_PASSWORD, ''),
            CKP::UNIX_SOCKET => env(EnvKey::DB_SOCKET, ''),
            CKP::CHARSET     => env(EnvKey::DB_CHARSET, 'utf8mb4'),
            CKP::COLLATION   => env(EnvKey::DB_COLLATION, 'utf8mb4_unicode_ci'),
            CKP::PREFIX      => env(EnvKey::DB_PREFIX, ''),
            CKP::STRICT      => env(EnvKey::DB_STRICT, true),
            CKP::ENGINE      => env(EnvKey::DB_ENGINE, null),
        ],

        CKP::PGSQL => [
            CKP::DRIVER   => CKP::PGSQL,
            CKP::HOST     => env(EnvKey::DB_HOST, '127.0.0.1'),
            CKP::PORT     => env(EnvKey::DB_PORT, '5432'),
            CKP::DB       => env(EnvKey::DB_DATABASE, 'forge'),
            CKP::USERNAME => env(EnvKey::DB_USERNAME, 'forge'),
            CKP::PASSWORD => env(EnvKey::DB_PASSWORD, ''),
            CKP::CHARSET  => env(EnvKey::DB_CHARSET, 'utf8'),
            CKP::PREFIX   => env(EnvKey::DB_PREFIX, ''),
            CKP::SCHEMA   => env(EnvKey::DB_SCHEME, 'public'),
            CKP::SSL_MODE => env(EnvKey::DB_SSL_MODE, 'prefer'),
        ],

        CKP::SQLSRV => [
            CKP::DRIVER   => CKP::SQLSRV,
            CKP::HOST     => env(EnvKey::DB_HOST, 'localhost'),
            CKP::PORT     => env(EnvKey::DB_PORT, '1433'),
            CKP::DB       => env(EnvKey::DB_DATABASE, 'forge'),
            CKP::USERNAME => env(EnvKey::DB_USERNAME, 'forge'),
            CKP::PASSWORD => env(EnvKey::DB_PASSWORD, ''),
            CKP::CHARSET  => env(EnvKey::DB_CHARSET, 'utf8'),
            CKP::PREFIX   => env(EnvKey::DB_PREFIX, ''),
        ],

    ],

];
