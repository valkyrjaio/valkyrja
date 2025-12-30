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

namespace Valkyrja\Api\Model;

use Override;
use Valkyrja\Api\Model\Contract\JsonData as Contract;
use Valkyrja\Type\Model\Abstract\Model;

/**
 * Class JsonData.
 *
 * @author Melech Mizrachi
 */
class JsonData extends Model implements Contract
{
    /**
     * The item.
     *
     * @var object|null
     */
    public object|null $item = null;

    /**
     * The item key.
     *
     * @var string
     */
    public string $itemKey = 'item';

    /**
     * The items.
     *
     * @var object[]|null
     */
    public array|null $items = null;

    /**
     * The items key.
     *
     * @var string
     */
    public string $itemsKey = 'items';

    /**
     * The total.
     *
     * @var int|null
     */
    public int|null $total = null;

    /**
     * The messages.
     *
     * @var string[]|null
     */
    public array|null $messages = null;

    /**
     * The data.
     *
     * @var array<array-key, mixed>|null
     */
    public array|null $data = null;

    /**
     * @inheritDoc
     */
    #[Override]
    public function getItem(): object|null
    {
        return $this->item;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setItem(object|null $item = null): static
    {
        $this->item = $item;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getItemKey(): string
    {
        return $this->itemKey;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setItemKey(string $itemKey): static
    {
        $this->itemKey = $itemKey;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getItems(): array|null
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setItems(array|null $items = null): static
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getItemsKey(): string
    {
        return $this->itemsKey;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setItemsKey(string $itemsKey): static
    {
        $this->itemsKey = $itemsKey;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getTotal(): int|null
    {
        return $this->total;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setTotal(int|null $total = null): static
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getMessages(): array|null
    {
        return $this->messages;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setMessages(array|null $messages = null): static
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getData(): array|null
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setData(array|null $data = null): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @psalm-suppress MixedReturnTypeCoercion
     */
    #[Override]
    public function asArray(string ...$properties): array
    {
        $data = $this->data ?? [];

        if ($this->messages !== null && $this->messages !== []) {
            $data['messages'] = $this->messages;
        }

        if ($this->item !== null) {
            $data[$this->itemKey] = $this->item;
        }

        if ($this->items !== null && $this->items !== []) {
            $data[$this->itemsKey] = $this->items;
        }

        if ($this->total !== null) {
            $data['total'] = $this->total;
        }

        return $data;
    }
}
