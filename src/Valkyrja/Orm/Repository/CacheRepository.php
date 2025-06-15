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

namespace Valkyrja\Orm\Repository;

use JsonException;
use Throwable;
use Valkyrja\Cache\Contract\Cache;
use Valkyrja\Cache\Driver\Contract\Driver as CacheDriver;
use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Exception\RuntimeException;
use Valkyrja\Orm\Contract\Orm;
use Valkyrja\Orm\Driver\Contract\Driver;
use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\Exception\EntityNotFoundException;
use Valkyrja\Orm\Persister\Contract\Persister;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilder;
use Valkyrja\Orm\Repository\Contract\CacheRepository as Contract;
use Valkyrja\Orm\Repository\Enum\StoreType;
use Valkyrja\Type\BuiltIn\Support\Arr;
use Valkyrja\Type\BuiltIn\Support\Obj;

use function base64_decode;
use function is_array;
use function is_int;
use function is_string;
use function md5;
use function method_exists;
use function serialize;
use function spl_object_id;
use function unserialize;

/**
 * Class CacheRepository.
 *
 * @author Melech Mizrachi
 *
 * @template Entity of Entity
 *
 * @extends Repository<Entity>
 *
 * @implements Contract<Entity>
 */
class CacheRepository extends Repository implements Contract
{
    /**
     * The cache store.
     *
     * @var CacheDriver
     */
    protected CacheDriver $store;

    /**
     * The id of a findOne (to tag if null returned).
     *
     * @var int|string|null
     */
    protected int|string|null $id;

    /**
     * The entities awaiting to be stored.
     *
     * @var Entity[]
     */
    protected array $storeEntities = [];

    /**
     * The entities awaiting to be forgotten.
     *
     * @var Entity[]
     */
    protected array $forgetEntities = [];

    /**
     * CacheRepository constructor.
     *
     * @param Orm                  $orm       The orm manager
     * @param Driver               $driver    The driver
     * @param Persister<Entity>    $persister The persister
     * @param Cache                $cache     The cache service
     * @param class-string<Entity> $entity    The entity class name
     */
    public function __construct(
        Orm $orm,
        Driver $driver,
        Persister $persister,
        protected Cache $cache,
        string $entity
    ) {
        $this->store = $cache->use();

        parent::__construct(
            orm: $orm,
            driver: $driver,
            persister: $persister,
            entity: $entity
        );
    }

    /**
     * @inheritDoc
     */
    public function findOne(int|string $id): static
    {
        parent::findOne($id);

        $this->id = $id;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function where(
        string $column,
        string|null $operator = null,
        mixed $value = null,
        bool $setType = true
    ): static {
        if (! ($value instanceof QueryBuilder) && $column === $this->entity::getIdField()) {
            if (! is_string($value) && ! is_int($value)) {
                throw new InvalidArgumentException('ID should be either a string or int');
            }

            $this->id = $value;
        }

        parent::where($column, $operator, $value, $setType);

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function getResult(): array
    {
        $cacheKey = $this->getCacheKey();

        if (($results = $this->store->get($cacheKey)) !== null && $results !== '') {
            try {
                $decodedResults = base64_decode($results, true);

                if ($decodedResults === false) {
                    throw new RuntimeException('Failed to decode results');
                }

                $results = unserialize($decodedResults, ['allowed_classes' => true]);

                if (! is_array($results)) {
                    throw new RuntimeException('Unserialized results were not an array');
                }

                if ($results === []) {
                    return [];
                }

                if (! $results[0] instanceof Entity) {
                    throw new RuntimeException('Unserialized results were not an array of entities');
                }

                if (method_exists($this, 'setRelationshipsOnEntities')) {
                    $this->setRelationshipsOnEntities(...$results);
                }

                /** @var Entity[] $results */
                return $results;
            } catch (Throwable) {
            }

            // Remove the bad cache
            $this->store->forget($cacheKey);
        }

        $results = $this->retriever->getResult();

        $this->cacheResults($cacheKey, $results);

        if (method_exists($this, 'setRelationshipsOnEntities')) {
            $this->setRelationshipsOnEntities(...$results);
        }

        $this->id = null;

        return $results;
    }

    /**
     * @inheritDoc
     */
    public function getOneOrFail(): Entity
    {
        $results = $this->getOneOrNull();

        if ($results === null) {
            throw new EntityNotFoundException('Entity Not Found');
        }

        return $results;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function getCount(): int
    {
        $cacheKey = $this->getCacheKey();

        if (($results = $this->store->get($cacheKey)) !== null && $results !== '') {
            return (int) $results;
        }

        $results = parent::getCount();

        $this->store->forever($cacheKey, (string) $results);

        $this->store->getTagger($this->entity)->tag($cacheKey);

        return $results;
    }

    /**
     * @inheritDoc
     */
    public function create(Entity $entity, bool $defer = true): void
    {
        parent::create($entity, $defer);

        $this->deferOrCache(StoreType::store, $entity, $defer);
    }

    /**
     * @inheritDoc
     */
    public function save(Entity $entity, bool $defer = true): void
    {
        parent::save($entity, $defer);

        $this->deferOrCache(StoreType::store, $entity, $defer);
    }

    /**
     * @inheritDoc
     */
    public function delete(Entity $entity, bool $defer = true): void
    {
        parent::delete($entity, $defer);

        $this->deferOrCache(StoreType::forget, $entity, $defer);
    }

    /**
     * @inheritDoc
     */
    public function clear(Entity|null $entity = null): void
    {
        parent::clear($entity);

        if ($entity === null) {
            $this->clearDeferred();

            return;
        }

        // Get the id of the object
        $id = spl_object_id($entity);

        // If the model is set to be stored
        if (isset($this->storeEntities[$id])) {
            // Unset it
            unset($this->storeEntities[$id]);

            return;
        }

        // If the model is set to be forgotten
        if (isset($this->forgetEntities[$id])) {
            // Unset it
            unset($this->forgetEntities[$id]);
        }
    }

    /**
     * @inheritDoc
     */
    public function persist(): bool
    {
        $persist = parent::persist();

        $this->persistSave();
        $this->persistDelete();
        $this->clearDeferred();

        return $persist;
    }

    /**
     * Get cache key.
     *
     * @throws JsonException
     *
     * @return string
     */
    protected function getCacheKey(): string
    {
        return md5(
            Arr::toString(Obj::getAllProperties($this->retriever))
            . Arr::toString(Obj::getAllProperties($this->retriever->getQueryBuilder()))
        );
    }

    /**
     * Defer or cache.
     *
     * @param StoreType $type   Whether to store or forget
     * @param Entity    $entity The entity
     * @param bool      $defer  [optional] Whether to defer
     *
     * @return void
     */
    protected function deferOrCache(StoreType $type, Entity $entity, bool $defer = true): void
    {
        if ($defer) {
            $this->setDeferredEntity($type, $entity);

            return;
        }

        $this->forgetEntity($entity);
    }

    /**
     * Set a deferred entity.
     *
     * @param StoreType $type
     * @param Entity    $entity
     *
     * @return void
     */
    protected function setDeferredEntity(StoreType $type, Entity $entity): void
    {
        $id = spl_object_id($entity);

        match ($type) {
            StoreType::store  => $this->storeEntities[$id]  = $entity,
            StoreType::forget => $this->forgetEntities[$id] = $entity,
        };
    }

    /**
     * Forget entity in cache.
     *
     * @param Entity $entity
     *
     * @return void
     */
    protected function forgetEntity(Entity $entity): void
    {
        $id = $this->getEntityCacheKey($entity);

        $this->store->getTagger($id)->flush();
    }

    /**
     * Get entity cache key.
     *
     * @param Entity $entity
     *
     * @return string
     */
    protected function getEntityCacheKey(Entity $entity): string
    {
        return $entity::class . ((string) $entity->getIdValue());
    }

    /**
     * Clear deferred entities.
     *
     * @return void
     */
    protected function clearDeferred(): void
    {
        $this->storeEntities  = [];
        $this->forgetEntities = [];
    }

    /**
     * Persist entities to be saved.
     *
     * @return void
     */
    protected function persistSave(): void
    {
        foreach ($this->storeEntities as $sid => $entity) {
            $this->forgetEntity($entity);

            unset($this->storeEntities[$sid]);
        }
    }

    /**
     * Persist entities to be deleted.
     *
     * @return void
     */
    protected function persistDelete(): void
    {
        foreach ($this->forgetEntities as $sid => $entity) {
            $this->forgetEntity($entity);

            unset($this->forgetEntities[$sid]);
        }
    }

    /**
     * Cache results.
     *
     * @param string                   $cacheKey
     * @param int|Entity|Entity[]|null $results
     *
     * @return void
     */
    protected function cacheResults(string $cacheKey, Entity|array|int|null $results): void
    {
        $id     = $this->id;
        $tags   = $id !== null && $id !== ''
            ? [(string) $this->id]
            : [];
        $tags[] = $this->entity;

        if (is_array($results)) {
            $tags = [];

            foreach ($results as $result) {
                $tags[] = $this->getEntityCacheKey($result);
            }
        }

        $this->store->forever($cacheKey, base64_encode(serialize($results)));

        $this->store->getTagger(...$tags)->tag($cacheKey);
    }
}
