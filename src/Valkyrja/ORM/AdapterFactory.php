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

namespace Valkyrja\ORM;

/**
 * Interface AdapterFactory.
 *
 * @author Melech Mizrachi
 */
interface AdapterFactory
{
    /**
     * Create an adapter.
     *
     * @template T
     *
     * @param class-string<T> $name   The adapter class name
     * @param array           $config The config
     *
     * @return T
     */
    public function createAdapter(string $name, array $config): Adapter;
}
