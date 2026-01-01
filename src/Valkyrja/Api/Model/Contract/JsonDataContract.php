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

namespace Valkyrja\Api\Model\Contract;

use Valkyrja\Type\Model\Contract\ModelContract;

/**
 * Interface JsonDataContract.
 *
 * @author Melech Mizrachi
 */
interface JsonDataContract extends ModelContract
{
    /**
     * Get the item.
     *
     * @return object|null
     */
    public function getItem(): object|null;

    /**
     * Set the item.
     *
     * @param object|null $item
     *
     * @return static
     */
    public function setItem(object|null $item = null): static;

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
    public function setItemKey(string $itemKey): static;

    /**
     * Get the items.
     *
     * @return object[]|null
     */
    public function getItems(): array|null;

    /**
     * Set the items.
     *
     * @param object[]|null $items
     *
     * @return static
     */
    public function setItems(array|null $items = null): static;

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
    public function setItemsKey(string $itemsKey): static;

    /**
     * Get the total.
     *
     * @return int|null
     */
    public function getTotal(): int|null;

    /**
     * Set the total.
     *
     * @param int|null $total
     *
     * @return static
     */
    public function setTotal(int|null $total = null): static;

    /**
     * Get the messages.
     *
     * @return string[]|null
     */
    public function getMessages(): array|null;

    /**
     * Set the messages.
     *
     * @param string[]|null $messages
     *
     * @return static
     */
    public function setMessages(array|null $messages = null): static;

    /**
     * Get the data.
     *
     * @return array<array-key, mixed>|null
     */
    public function getData(): array|null;

    /**
     * Set the data.
     *
     * @param array<array-key, mixed>|null $data
     *
     * @return static
     */
    public function setData(array|null $data = null): static;
}
