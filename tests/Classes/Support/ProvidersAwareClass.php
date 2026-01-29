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

namespace Valkyrja\Tests\Classes\Support;

use Valkyrja\Container\Manager\Trait\ProvidersAware;

use function array_key_exists;

/**
 * Class ProvidersAwareClass.
 */
class ProvidersAwareClass
{
    use ProvidersAware;

    private array $objects = [];

    public function __get(string $name)
    {
        return $this->objects[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->objects[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return array_key_exists($name, $this->objects);
    }
}
