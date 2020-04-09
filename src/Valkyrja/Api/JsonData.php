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

namespace Valkyrja\Api;

use Valkyrja\Support\Model\Model;

/**
 * Interface JsonData.
 *
 * @author Melech Mizrachi
 */
interface JsonData extends Model
{
    /**
     * Get the item.
     *
     * @return object|null
     */
    public function getItem(): ?object;

    /**
     * Set the item.
     *
     * @param object|null $item
     *
     * @return static
     */
    public function setItem(object $item = null): self;

    /**
     * Get the item key.
     *
     * @return string
     */
    public function getItemKey(): string;

    /**
     * Set the item key.
     *
     * @param string $itemKey
     *
     * @return static
     */
    public function setItemKey(string $itemKey): self;

    /**
     * Get the items.
     *
     * @return array|null
     */
    public function getItems(): ?array;

    /**
     * Set the items.
     *
     * @param array|null $items
     *
     * @return static
     */
    public function setItems(array $items = null): self;

    /**
     * Get the items key.
     *
     * @return string
     */
    public function getItemsKey(): string;

    /**
     * Set the items key.
     *
     * @param string $itemsKey
     *
     * @return static
     */
    public function setItemsKey(string $itemsKey): self;

    /**
     * Get the total.
     *
     * @return int|null
     */
    public function getTotal(): ?int;

    /**
     * Set the total.
     *
     * @param int|null $total
     *
     * @return static
     */
    public function setTotal(int $total = null): self;

    /**
     * Get the messages.
     *
     * @return array|null
     */
    public function getMessages(): ?array;

    /**
     * Set the messages.
     *
     * @param array|null $messages
     *
     * @return static
     */
    public function setMessages(array $messages = null): self;

    /**
     * Get the data.
     *
     * @return array|null
     */
    public function getData(): ?array;

    /**
     * Set the data.
     *
     * @param array|null $data
     *
     * @return static
     */
    public function setData(array $data = null): self;
}
