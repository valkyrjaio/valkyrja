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

namespace Valkyrja\Cache\Tagger;

use JsonException;
use Override;
use Valkyrja\Cache\Contract\Cache;
use Valkyrja\Cache\Tagger\Contract\Tagger as Contract;
use Valkyrja\Type\BuiltIn\Support\Arr;

/**
 * Class Cache.
 *
 * @author Melech Mizrachi
 */
class Tagger implements Contract
{
    /**
     * The tags.
     *
     * @var string[]
     */
    protected array $tags;

    /**
     * Tag constructor.
     *
     * @param Cache  $adapter
     * @param string ...$tags
     */
    public function __construct(
        protected Cache $adapter,
        string ...$tags
    ) {
        $this->tags = $tags;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function make(Cache $store, string ...$tags): static
    {
        return new static($store, ...$tags);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function has(string $key): bool
    {
        foreach ($this->tags as $tag) {
            if (isset($this->getKeys($tag)[$key]) && $this->adapter->has($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function get(string $key): string|null
    {
        foreach ($this->tags as $tag) {
            if (isset($this->getKeys($tag)[$key])) {
                return $this->adapter->get($key);
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function many(string ...$keys): array
    {
        $items = [];

        foreach ($this->tags as $tag) {
            $cachedKeys = $this->getKeys($tag);

            foreach ($keys as $key) {
                if (isset($cachedKeys[$key])) {
                    $value = $this->adapter->get($key);

                    if ($value === null) {
                        continue;
                    }

                    $items[] = $value;
                }
            }
        }

        return $items;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function put(string $key, string $value, int $minutes): void
    {
        $this->tag($key);

        $this->adapter->put($key, $value, $minutes);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function putMany(array $values, int $minutes): void
    {
        foreach ($values as $key => $value) {
            $this->put($key, $value, $minutes);
        }
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function increment(string $key, int $value = 1): int
    {
        $this->tag($key);

        return $this->adapter->increment($key, $value);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function decrement(string $key, int $value = 1): int
    {
        $this->tag($key);

        return $this->adapter->decrement($key, $value);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function forever(string $key, string $value): void
    {
        $this->tag($key);

        $this->adapter->forever($key, $value);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function forget(string $key): bool
    {
        $this->untag($key);

        return $this->adapter->forget($key);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function flush(): bool
    {
        foreach ($this->tags as $tag) {
            foreach ($this->getKeys($tag) as $key) {
                $this->adapter->forget($key);
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function tag(string $key): static
    {
        foreach ($this->tags as $tag) {
            $keys = $this->getKeys($tag);

            $keys[$key] = $key;

            $this->putKeys($tag, $keys);
        }

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function untag(string $key): static
    {
        foreach ($this->tags as $tag) {
            $keys = $this->getKeys($tag);

            unset($keys[$key]);

            $this->putKeys($tag, $keys);
        }

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function tagMany(string ...$keys): static
    {
        foreach ($keys as $key) {
            $this->tag($key);
        }

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function untagMany(string ...$keys): static
    {
        foreach ($keys as $key) {
            $this->untag($key);
        }

        return $this;
    }

    /**
     * Get a tag.
     *
     * @param string $tag
     *
     * @throws JsonException
     *
     * @return string[]
     */
    protected function getKeys(string $tag): array
    {
        $keysFromCache = $this->adapter->get($tag);

        if ($keysFromCache !== null && $keysFromCache !== '') {
            /** @var string[] $keys */
            $keys = Arr::fromString($keysFromCache);

            return $keys;
        }

        return [];
    }

    /**
     * Put a tag.
     *
     * @param string   $tag
     * @param string[] $keys
     *
     * @throws JsonException
     *
     * @return void
     */
    protected function putKeys(string $tag, array $keys): void
    {
        $this->adapter->forever($tag, Arr::toString($keys));
    }
}
