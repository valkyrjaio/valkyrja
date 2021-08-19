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

namespace Valkyrja\ORM\Retrievers;

use JsonException;
use Valkyrja\ORM\Constants\Statement;
use Valkyrja\Support\Type\Arr;

use function is_array;

/**
 * Class Retriever
 *
 * @author Melech Mizrachi
 */
class LocalCacheRetriever extends Retriever
{
    /**
     * Local cache for results in case same query is made multiple times within the same session.
     *
     * @var array
     */
    protected static array $localCache = [];

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function getResult(): array
    {
        $localCacheKey = $this->getCacheKey();

        if (isset(self::$localCache[$localCacheKey])) {
            return self::$localCache[$localCacheKey];
        }

        $this->prepareResults();

        $results = $this->query->getResult();

        if ($this->getRelations && is_array($results)) {
            $this->setRelationshipsOnEntities($this->relationships, ...$results);
        }

        self::$localCache[$localCacheKey] = $results;

        return $results;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function getCount(): int
    {
        $this->columns([Statement::COUNT_ALL]);

        $localCacheKey = $this->getCacheKey();

        if (isset(self::$localCache[$localCacheKey])) {
            return self::$localCache[$localCacheKey];
        }

        $this->prepareResults();

        $count = $this->query->getCount();

        self::$localCache[$localCacheKey] = $count;

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
