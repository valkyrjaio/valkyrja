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

namespace Valkyrja\Api\Models;

use Valkyrja\Model\ModelTrait;

/**
 * Trait JsonDataTrait.
 *
 * @author Melech Mizrachi
 */
trait JsonDataTrait
{
    use ModelTrait;

    /**
     * The item.
     *
     * @var object|null
     */
    protected ?object $item = null;

    /**
     * The item key.
     *
     * @var string
     */
    protected string $itemKey = 'item';

    /**
     * The items.
     *
     * @var object[]|null
     */
    protected ?array $items = null;

    /**
     * The items key
     *
     * @var string
     */
    protected string $itemsKey = 'items';

    /**
     * The total.
     *
     * @var int|null
     */
    protected ?int $total = null;

    /**
     * The message.
     *
     * @var string|null
     */
    protected ?string $message = null;

    /**
     * The messages.
     *
     * @var array|null
     */
    protected ?array $messages = null;

    /**
     * The data.
     *
     * @var array|null
     */
    protected ?array $data = null;

    /**
     * Get the item.
     *
     * @return object|null
     */
    public function getItem(): ?object
    {
        return $this->item;
    }

    /**
     * Set the item.
     *
     * @param object|null $item
     *
     * @return static
     */
    public function setItem(object $item = null): self
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get the item key.
     *
     * @return string
     */
    public function getItemKey(): string
    {
        return $this->itemKey;
    }

    /**
     * Set the item key.
     *
     * @param string $itemKey
     *
     * @return static
     */
    public function setItemKey(string $itemKey): self
    {
        $this->itemKey = $itemKey;

        return $this;
    }

    /**
     * Get the items.
     *
     * @return array|null
     */
    public function getItems(): ?array
    {
        return $this->items;
    }

    /**
     * Set the items.
     *
     * @param array|null $items
     *
     * @return static
     */
    public function setItems(array $items = null): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Get the items key.
     *
     * @return string
     */
    public function getItemsKey(): string
    {
        return $this->itemsKey;
    }

    /**
     * Set the items key.
     *
     * @param string $itemsKey
     *
     * @return static
     */
    public function setItemsKey(string $itemsKey): self
    {
        $this->itemsKey = $itemsKey;

        return $this;
    }

    /**
     * Get the total.
     *
     * @return int|null
     */
    public function getTotal(): ?int
    {
        return $this->total;
    }

    /**
     * Set the total.
     *
     * @param int|null $total
     *
     * @return static
     */
    public function setTotal(int $total = null): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get the message.
     *
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Set the error message.
     *
     * @param string|null $message
     *
     * @return static
     */
    public function setMessage(string $message = null): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the messages.
     *
     * @return array|null
     */
    public function getMessages(): ?array
    {
        return $this->messages;
    }

    /**
     * Set the messages.
     *
     * @param array|null $messages
     *
     * @return static
     */
    public function setMessages(array $messages = null): self
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * Get the data.
     *
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * Set the data.
     *
     * @param array|null $data
     *
     * @return static
     */
    public function setData(array $data = null): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Serialize properties for json_encode.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $data = $this->data ?? [];

        if ($this->messages) {
            $data['messages'] = $this->messages;
        }

        if ($this->item) {
            $data[$this->itemKey] = $this->item;
        }

        if ($this->items) {
            $data[$this->itemsKey] = $this->items;
        }

        if ($this->total) {
            $data['total'] = $this->total;
        }

        return $data;
    }
}
