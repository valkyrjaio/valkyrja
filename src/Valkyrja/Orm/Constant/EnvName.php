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
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const string DEFAULT_CONNECTION = 'ORM_DEFAULT_CONNECTION';
    public const string CONNECTIONS        = 'ORM_CONNECTIONS';
    public const string MIGRATIONS         = 'ORM_MIGRATIONS';

    public const string MYSQL_ADAPTER_CLASS       = 'ORM_MYSQL_ADAPTER_CLASS';
    public const string MYSQL_DRIVER_CLASS        = 'ORM_MYSQL_DRIVER_CLASS';
    public const string MYSQL_REPOSITORY_CLASS    = 'ORM_MYSQL_REPOSITORY_CLASS';
    public const string MYSQL_QUERY_CLASS         = 'ORM_MYSQL_QUERY_CLASS';
    public const string MYSQL_QUERY_BUILDER_CLASS = 'ORM_MYSQL_QUERY_BUILDER_CLASS';
    public const string MYSQL_PERSISTER_CLASS     = 'ORM_MYSQL_PERSISTER_CLASS';
    public const string MYSQL_RETRIEVER_CLASS     = 'ORM_MYSQL_RETRIEVER_CLASS';
    public const string MYSQL_PDO_CLASS           = 'ORM_MYSQL_PDO_CLASS';
    public const string MYSQL_PDO_DRIVER          = 'ORM_MYSQL_PDO_DRIVER';
    public const string MYSQL_HOST                = 'ORM_MYSQL_HOST';
    public const string MYSQL_PORT                = 'ORM_MYSQL_PORT';
    public const string MYSQL_DB                  = 'ORM_MYSQL_DB';
    public const string MYSQL_USER                = 'ORM_MYSQL_USER';
    public const string MYSQL_PASSWORD            = 'ORM_MYSQL_PASSWORD';
    public const string MYSQL_CHARSET             = 'ORM_MYSQL_CHARSET';
    public const string MYSQL_OPTIONS             = 'ORM_MYSQL_OPTIONS';
    public const string MYSQL_STRICT              = 'ORM_MYSQL_STRICT';
    public const string MYSQL_ENGINE              = 'ORM_MYSQL_ENGINE';

    public const string PGSQL_ADAPTER_CLASS       = 'ORM_PGSQL_ADAPTER_CLASS';
    public const string PGSQL_DRIVER_CLASS        = 'ORM_PGSQL_DRIVER_CLASS';
    public const string PGSQL_REPOSITORY_CLASS    = 'ORM_PGSQL_REPOSITORY_CLASS';
    public const string PGSQL_QUERY_CLASS         = 'ORM_PGSQL_QUERY_CLASS';
    public const string PGSQL_QUERY_BUILDER_CLASS = 'ORM_PGSQL_QUERY_BUILDER_CLASS';
    public const string PGSQL_PERSISTER_CLASS     = 'ORM_PGSQL_PERSISTER_CLASS';
    public const string PGSQL_RETRIEVER_CLASS     = 'ORM_PGSQL_RETRIEVER_CLASS';
    public const string PGSQL_PDO_CLASS           = 'ORM_PGSQL_PDO_CLASS';
    public const string PGSQL_PDO_DRIVER          = 'ORM_PGSQL_PDO_DRIVER';
    public const string PGSQL_HOST                = 'ORM_PGSQL_HOST';
    public const string PGSQL_PORT                = 'ORM_PGSQL_PORT';
    public const string PGSQL_DB                  = 'ORM_PGSQL_DB';
    public const string PGSQL_USER                = 'ORM_PGSQL_USER';
    public const string PGSQL_PASSWORD            = 'ORM_PGSQL_PASSWORD';
    public const string PGSQL_CHARSET             = 'ORM_PGSQL_CHARSET';
    public const string PGSQL_OPTIONS             = 'ORM_PGSQL_OPTIONS';
    public const string PGSQL_SCHEMA              = 'ORM_PGSQL_SCHEMA';
    public const string PGSQL_SSL_MODE            = 'ORM_PGSQL_SSL_MODE';
    public const string PGSQL_SSL_CERT            = 'ORM_PGSQL_SSL_CERT';
    public const string PGSQL_KEY                 = 'ORM_PGSQL_KEY';
    public const string PGSQL_ROOT_KEY            = 'ORM_PGSQL_ROOT_KEY';
}
