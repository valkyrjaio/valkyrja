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

namespace Valkyrja\ORM\Repositories;

use JsonException;
use Valkyrja\Cache\Cache;
use Valkyrja\Cache\Driver as CacheDriver;
use Valkyrja\ORM\CacheRepository as Contract;
use Valkyrja\ORM\Driver;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\Exceptions\EntityNotFoundException;
use Valkyrja\ORM\ORM;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\SoftDeleteEntity;
use Valkyrja\Support\Type\Arr;
use Valkyrja\Support\Type\Obj;

use function get_class;
use function is_array;
use function md5;
use function serialize;
use function spl_object_id;
use function unserialize;

/**
 * Class CacheRepository.
 *
 * @author Melech Mizrachi
 */
class CacheRepository extends Repository implements Contract
{
    /**
     * Store type.
     *
     * @var string
     */
    protected static string $storeType = 'store';

    /**
     * Forget type.
     *
     * @var string
     */
    protected static string $forgetType = 'forget';

    /**
     * The cache.
     *
     * @var Cache
     */
    protected Cache $cache;

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
     * Repository constructor.
     *
     * @param ORM                  $manager The orm manager
     * @param Driver               $driver  The driver
     * @param Cache                $cache   The cache service
     * @param class-string<Entity> $entity  The entity class name
     */
    public function __construct(ORM $manager, Driver $driver, Cache $cache, string $entity)
    {
        $this->cache = $cache;
        $this->store = $cache->use();

        parent::__construct($manager, $driver, $entity);
    }

    /**
     * @inheritDoc
     */
    public function findOne(int|string $id): self
    {
        parent::findOne($id);

        $this->id = $id;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function where(string $column, string $operator = null, mixed $value = null, bool $setType = true): self
    {
        if (! ($value instanceof QueryBuilder) && $column === $this->entity::getIdField()) {
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

        if ($results = $this->store->get($cacheKey)) {
            $results = unserialize(base64_decode($results, true), ['allowed_classes' => true]);

            $this->setRelationshipsOnEntities(...$results);

            return $results;
        }

        $results = $this->retriever->getResult();

        $this->cacheResults($cacheKey, $results);

        $this->setRelationshipsOnEntities(...$results);

        $this->id = null;

        return $results;
    }

    /**
     * @inheritDoc
     */
    public function getOneOrFail(): Entity
    {
        $results = $this->getOneOrNull();

        if (null === $results) {
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

        if ($results = $this->store->get($cacheKey)) {
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

        $this->deferOrCache(self::$storeType, $entity, $defer);
    }

    /**
     * @inheritDoc
     */
    public function save(Entity $entity, bool $defer = true): void
    {
        parent::save($entity, $defer);

        $this->deferOrCache(self::$storeType, $entity, $defer);
    }

    /**
     * @inheritDoc
     */
    public function delete(Entity $entity, bool $defer = true): void
    {
        parent::delete($entity, $defer);

        $this->deferOrCache(self::$forgetType, $entity, $defer);
    }

    /**
     * @inheritDoc
     *
     * @param SoftDeleteEntity $entity The entity
     */
    public function softDelete(SoftDeleteEntity $entity, bool $defer = true): void
    {
        parent::softDelete($entity, $defer);

        $this->deferOrCache(self::$storeType, $entity, $defer);
    }

    /**
     * @inheritDoc
     *
     * @param Entity|null $entity The entity instance to remove.
     */
    public function clear(Entity $entity = null): void
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
     * @param string $type
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @return void
     */
    protected function deferOrCache(string $type, Entity $entity, bool $defer = true): void
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
     * @param string $type
     * @param Entity $entity
     *
     * @return void
     */
    protected function setDeferredEntity(string $type, Entity $entity): void
    {
        $id = spl_object_id($entity);

        match ($type) {
            self::$storeType  => $this->storeEntities[$id] = $entity,
            self::$forgetType => $this->forgetEntities[$id] = $entity,
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
        return get_class($entity) . $entity->__get($entity::getIdField());
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
        $tags   = $this->id ? [$this->id] : [];
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
