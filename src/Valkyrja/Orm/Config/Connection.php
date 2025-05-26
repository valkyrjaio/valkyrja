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

namespace Valkyrja\Orm\Config;

use Valkyrja\Config\DataConfig as ParentConfig;
use Valkyrja\Orm\Adapter\Contract\Adapter as AdapterContract;
use Valkyrja\Orm\Adapter\PdoAdapter;
use Valkyrja\Orm\Driver\Contract\Driver as DriverContract;
use Valkyrja\Orm\Driver\Driver;
use Valkyrja\Orm\Persister\Contract\Persister as PersisterContract;
use Valkyrja\Orm\Persister\Persister;
use Valkyrja\Orm\Query\Contract\Query as QueryContract;
use Valkyrja\Orm\Query\Query;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilder as QueryBuilderContract;
use Valkyrja\Orm\QueryBuilder\SqlQueryBuilder;
use Valkyrja\Orm\Repository\Contract\Repository as RepositoryContract;
use Valkyrja\Orm\Repository\Repository;
use Valkyrja\Orm\Retriever\Contract\Retriever as RetrieverContract;
use Valkyrja\Orm\Retriever\Retriever;

/**
 * Abstract Class Connection.
 *
 * @author Melech Mizrachi
 */
abstract class Connection extends ParentConfig
{
    /**
     * @param class-string<AdapterContract>      $adapterClass
     * @param class-string<DriverContract>       $driverClass
     * @param class-string<RepositoryContract>   $repositoryClass
     * @param class-string<QueryContract>        $queryClass
     * @param class-string<QueryBuilderContract> $queryBuilderClass
     * @param class-string<PersisterContract>    $persisterClass
     * @param class-string<RetrieverContract>    $retrieverClass
     */
    public function __construct(
        public string $adapterClass = PdoAdapter::class,
        public string $driverClass = Driver::class,
        public string $repositoryClass = Repository::class,
        public string $queryClass = Query::class,
        public string $queryBuilderClass = SqlQueryBuilder::class,
        public string $persisterClass = Persister::class,
        public string $retrieverClass = Retriever::class,
    ) {
    }
}
