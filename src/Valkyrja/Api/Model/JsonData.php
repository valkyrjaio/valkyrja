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

use Valkyrja\Api\Model\Contract\JsonData as Contract;
use Valkyrja\Type\Model\Model;

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
    public ?object $item = null;

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
    public ?array $items = null;

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
    public ?int $total = null;

    /**
     * The messages.
     *
     * @var string[]|null
     */
    public ?array $messages = null;

    /**
     * The data.
     *
     * @var array<string, mixed>|null
     */
    public ?array $data = null;

    /**
     * @inheritDoc
     */
    public function getItem(): ?object
    {
        return $this->item;
    }

    /**
     * @inheritDoc
     */
    public function setItem(?object $item = null): static
    {
        $this->item = $item;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getItemKey(): string
    {
        return $this->itemKey;
    }

    /**
     * @inheritDoc
     */
    public function setItemKey(string $itemKey): static
    {
        $this->itemKey = $itemKey;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getItems(): ?array
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function setItems(?array $items = null): static
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getItemsKey(): string
    {
        return $this->itemsKey;
    }

    /**
     * @inheritDoc
     */
    public function setItemsKey(string $itemsKey): static
    {
        $this->itemsKey = $itemsKey;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTotal(): ?int
    {
        return $this->total;
    }

    /**
     * @inheritDoc
     */
    public function setTotal(?int $total = null): static
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMessages(): ?array
    {
        return $this->messages;
    }

    /**
     * @inheritDoc
     */
    public function setMessages(?array $messages = null): static
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function setData(?array $data = null): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @inheritDoc
     */
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
