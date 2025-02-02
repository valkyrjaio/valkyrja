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

namespace Valkyrja\Orm\Retriever;

use JsonException;
use Valkyrja\Orm\Constant\Statement;
use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Type\BuiltIn\Support\Arr;

/**
 * Class Retriever.
 *
 * @author   Melech Mizrachi
 *
 * @template Entity of Entity
 *
 * @extends Retriever<Entity>
 */
class LocalCacheRetriever extends Retriever
{
    /**
     * Local cache for results in case same query is made multiple times within the same session.
     *
     * @var array<string, Entity[]>
     */
    protected array $localCache = [];

    /**
     * Local cache for results in case same query is made multiple times within the same session.
     *
     * @var array<string, int>
     */
    protected array $localCountCache = [];

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function getResult(): array
    {
        $localCacheKey = $this->getCacheKey();

        if (isset($this->localCache[$localCacheKey])) {
            return $this->localCache[$localCacheKey];
        }

        $this->prepareResults();

        /** @var Entity[] $results */
        $results = $this->query->getResult();

        $this->localCache[$localCacheKey] = $results;

        return $results;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function getCount(): int
    {
        $this->columns(Statement::COUNT_ALL);

        $localCacheKey = $this->getCacheKey();

        if (isset($this->localCountCache[$localCacheKey])) {
            return $this->localCountCache[$localCacheKey];
        }

        $this->prepareResults();

        $count = $this->query->getCount();

        $this->localCountCache[$localCacheKey] = $count;

        return $count;
    }

    /**
     * Get the cache key.
     *
     * @throws JsonException
     *
     * @return string
     */
    protected function getCacheKey(): string
    {
        return $this->queryBuilder->getQueryString() . Arr::toString($this->values);
    }
}
