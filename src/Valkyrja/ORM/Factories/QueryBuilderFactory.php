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

namespace Valkyrja\ORM\Factories;

use Valkyrja\Container\Container;
use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\QueryBuilderFactory as Contract;
use Valkyrja\Support\Type\Cls;

/**
 * Class QueryBuilderFactory.
 *
 * @author Melech Mizrachi
 */
class QueryBuilderFactory implements Contract
{
    /**
     * The container service.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * AdapterFactory constructor.
     *
     * @param Container $container The container service
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function createQueryBuilder(Adapter $adapter, string $name): QueryBuilder
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            QueryBuilder::class,
            [$adapter]
        );
    }
}
