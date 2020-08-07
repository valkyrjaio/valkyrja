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
use Valkyrja\Cache\Store;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\Exceptions\EntityNotFoundException;
use Valkyrja\ORM\Exceptions\InvalidEntityException;
use Valkyrja\ORM\ORM;
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
class CacheRepository extends Repository
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
     * @var Store
     */
    protected Store $store;

    /**
     * The id of a findOne (to tag if null returned).
     *
     * @var string|int|null
     */
    protected $id;

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
     * @param ORM    $manager
     * @param Cache  $cache
     * @param string $entity
     */
    public function __construct(ORM $manager, Cache $cache, string $entity)
    {
        $this->cache = $cache;
        $this->store = $cache->getStore();

        parent::__construct($manager, $entity);
    }

    /**
     * Find a single entity given its id.
     *
     * @param string|int $id
     *
     * @return static
     */
    public function findOne($id): self
    {
        parent::findOne($id);

        $this->id = $id;

        return $this;
    }

    /**
     * Add a where condition.
     * - Each additional use will add an `AND` where condition.
     *
     * @param string      $column
     * @param string|null $operator
     * @param mixed|null  $value
     *
     * @return static
     */
    public function where(string $column, string $operator = null, $value = null): self
    {
        if ($column === $this->entity::getIdField()) {
            $this->id = $value;
        }

        parent::where($column, $operator, $value);

        return $this;
    }

    /**
     * Add an additional `OR` where condition.
     *
     * @param string      $column
     * @param string|null $operator
     * @param mixed|null  $value
     *
     * @return static
     */
    public function orWhere(string $column, string $operator = null, $value = null): self
    {
        if ($column === $this->entity::getIdField()) {
            $this->id = $value;
        }

        parent::orWhere($column, $operator, $value);

        return $this;
    }

    /**
     * Get results.
     *
     * @throws JsonException
     *
     * @return Entity[]
     */
    public function getResult(): array
    {
        $cacheKey = $this->getCacheKey();

        if ($results = $this->store->get($cacheKey)) {
            return unserialize($results, ['allowed_classes' => [Entity::class]]);
        }

        $results = parent::getResult();

        $this->cacheResults($cacheKey, $results);

        $this->id = null;

        return $results;
    }

    /**
     * Get one or null.
     *
     * @throws JsonException
     *
     * @return Entity|null
     */
    public function getOneOrNull(): ?Entity
    {
        return $this->getResult()[0] ?? null;
    }

    /**
     * Get one or fail.
     *
     * @throws EntityNotFoundException
     * @throws JsonException
     *
     * @return Entity
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
     * Get count results.
     *
     * @throws JsonException
     *
     * @return int
     */
    public function getCount(): int
    {
        $cacheKey = $this->getCacheKey();

        if ($results = $this->store->has($cacheKey)) {
            return (int) $results;
        }

        $results = parent::getCount();

        $this->store->forever($cacheKey, (string) $results);

        $this->store->getTagger($this->entity)->tag($cacheKey);

        return $results;
    }

    /**
     * Create a new entity.
     *
     * <code>
     *      $repository->create(new Entity(), true | false)
     * </code>
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @throws InvalidEntityException
     * @throws JsonException
     *
     * @return void
     */
    public function create(Entity $entity, bool $defer = true): void
    {
        parent::create($entity, $defer);

        $this->deferOrCache(self::$storeType, $entity, $defer);
    }

    /**
     * Update an existing entity.
     *
     * <code>
     *      $repository->save(new Entity(), true | false)
     * </code>
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @throws InvalidEntityException
     * @throws JsonException
     *
     * @return void
     */
    public function save(Entity $entity, bool $defer = true): void
    {
        parent::save($entity, $defer);

        $this->deferOrCache(self::$storeType, $entity, $defer);
    }

    /**
     * Delete an existing entity.
     *
     * <code>
     *      $repository->delete(new Entity(), true | false)
     * </code>
     *
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @throws InvalidEntityException
     * @throws JsonException
     *
     * @return void
     */
    public function delete(Entity $entity, bool $defer = true): void
    {
        parent::delete($entity, $defer);

        $this->deferOrCache(self::$forgetType, $entity, $defer);
    }

    /**
     * Soft delete an existing entity.
     *
     * <code>
     *      $persister->softDelete(new SoftDeleteEntity(), true | false)
     * </code>
     *
     * @param SoftDeleteEntity $entity
     * @param bool             $defer [optional]
     *
     * @throws InvalidEntityException
     * @throws JsonException
     *
     * @return void
     */
    public function softDelete(SoftDeleteEntity $entity, bool $defer = true): void
    {
        parent::softDelete($entity, $defer);

        $this->deferOrCache(self::$storeType, $entity, $defer);
    }

    /**
     * Clear all, or a single, deferred entity.
     *
     * <code>
     *      $repository->clear(new Entity())
     * </code>
     *
     * @param Entity|null $entity The entity
     *
     * @throws InvalidEntityException
     *
     * @return void
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

            return;
        }
    }

    /**
     * Persist all entities.
     *
     * @throws JsonException
     *
     * @return bool
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
        return md5(Obj::toString($this->retriever) . $this->getRelations);
    }

    /**
     * Defer or cache.
     *
     * @param string $type
     * @param Entity $entity
     * @param bool   $defer [optional]
     *
     * @throws JsonException
     *
     * @return void
     */
    protected function deferOrCache(string $type, Entity $entity, bool $defer = true): void
    {
        if ($defer) {
            $this->setDeferredEntity($type, $entity);

            return;
        }

        if ($type === self::$forgetType) {
            $this->forgetEntity($entity);

            return;
        }

        $this->storeEntity($entity);
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

        switch ($type) {
            case self::$storeType:
                $this->storeEntities[$id] = $entity;

                break;
            case self::$forgetType:
                $this->forgetEntities[$id] = $entity;

                break;
        }
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
     * Store entity in cache.
     *
     * @param Entity $entity
     *
     * @throws JsonException
     *
     * @return void
     */
    protected function storeEntity(Entity $entity): void
    {
        $id = $this->getEntityCacheKey($entity);

        $tag = $this->store->getTagger($id);

        $tag->flush();
        $tag->forever($id, Arr::toString($entity->asArray()));
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
        return get_class($entity) . $entity->getIdFieldValue();
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
     * @throws JsonException
     *
     * @return void
     */
    protected function persistSave(): void
    {
        foreach ($this->storeEntities as $sid => $entity) {
            $this->storeEntity($entity);

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
     * @param Entity[]|Entity|int|null $results
     *
     * @return void
     */
    protected function cacheResults(string $cacheKey, $results): void
    {
        $tags   = $this->id ? [$this->id] : [];
        $tags[] = $this->entity;

        if (is_array($results)) {
            $tags = [];

            foreach ($results as $result) {
                $tags[] = $this->getEntityCacheKey($result);
            }
        }

        $this->store->forever($cacheKey, serialize($results));

        $this->store->getTagger(...$tags)->tag($cacheKey);
    }
}
