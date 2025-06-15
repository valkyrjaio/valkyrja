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
    public const DEFAULT_CONNECTION = 'defaultConnection';
    public const CONNECTIONS        = 'connections';
    public const MIGRATIONS         = 'migrations';

    public const ADAPTER_CLASS       = 'adapterClass';
    public const DRIVER_CLASS        = 'driverClass';
    public const REPOSITORY_CLASS    = 'repositoryClass';
    public const QUERY_CLASS         = 'queryClass';
    public const QUERY_BUILDER_CLASS = 'queryBuilderClass';
    public const PERSISTER_CLASS     = 'persisterClass';
    public const RETRIEVER_CLASS     = 'retrieverClass';

    public const PDO_CLASS  = 'pdoClass';
    public const PDO_DRIVER = 'pdoDriver';
    public const HOST       = 'host';
    public const PORT       = 'port';
    public const DB         = 'db';
    public const USER       = 'user';
    public const PASSWORD   = 'password';
    public const CHARSET    = 'charset';
    public const OPTIONS    = 'options';
}
