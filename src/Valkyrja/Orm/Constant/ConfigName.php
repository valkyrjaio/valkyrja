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

namespace Valkyrja\Orm\Constant;

/**
 * Class ConfigName.
 *
 * @author Melech Mizrachi
 */
final class ConfigName
{
    public const string DEFAULT_CONNECTION = 'defaultConnection';
    public const string CONNECTIONS        = 'connections';
    public const string MIGRATIONS         = 'migrations';

    public const string ADAPTER_CLASS       = 'adapterClass';
    public const string DRIVER_CLASS        = 'driverClass';
    public const string REPOSITORY_CLASS    = 'repositoryClass';
    public const string QUERY_CLASS         = 'queryClass';
    public const string QUERY_BUILDER_CLASS = 'queryBuilderClass';
    public const string PERSISTER_CLASS     = 'persisterClass';
    public const string RETRIEVER_CLASS     = 'retrieverClass';

    public const string PDO_CLASS    = 'pdoClass';
    public const string PDO_DRIVER   = 'pdoDriver';
    public const string HOST         = 'host';
    public const string PORT         = 'port';
    public const string DB           = 'db';
    public const string USER         = 'user';
    public const string PASSWORD     = 'password';
    public const string CHARSET      = 'charset';
    public const string OPTIONS      = 'options';
    public const string STRICT       = 'strict';
    public const string ENGINE       = 'engine';
    public const string SCHEME       = 'schema';
    public const string SSL_MODE     = 'sslMode';
    public const string SSL_CERT     = 'sslCert';
    public const string SSL_KEY      = 'sslKey';
    public const string SSL_ROOT_KEY = 'sslRootKey';
}
