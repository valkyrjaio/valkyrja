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
     * The message.
     *
     * @var string|null
     */
    public string|null $message = null;

    /**
     * The messages.
     *
     * @var string[]|null
     */
    public array|null $messages = null;

    /**
     * The data.
     *
     * @var array<string, mixed>|null
     */
    public array|null $data = null;

    /**
     * @inheritDoc
     */
    public function getItem(): object|null
    {
        return $this->item;
    }

    /**
     * @inheritDoc
     */
    public function setItem(object|null $item = null): static
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
    public function getItems(): array|null
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function setItems(array|null $items = null): static
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
    public function getTotal(): int|null
    {
        return $this->total;
    }

    /**
     * @inheritDoc
     */
    public function setTotal(int|null $total = null): static
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMessages(): array|null
    {
        return $this->messages;
    }

    /**
     * @inheritDoc
     */
    public function setMessages(array|null $messages = null): static
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getData(): array|null
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function setData(array|null $data = null): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string|null
    {
        return $this->message;
    }

    /**
     * @inheritDoc
     */
    public function setMessage(string|null $message = null): static
    {
        $this->message = $message;

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
