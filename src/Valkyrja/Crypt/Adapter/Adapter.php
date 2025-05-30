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

namespace Valkyrja\Crypt\Adapter;

use Valkyrja\Crypt\Adapter\Contract\Adapter as Contract;
use Valkyrja\Exception\InvalidArgumentException;

use function is_string;

/**
 * Abstract Class Adapter.
 *
 * @author Melech Mizrachi
 */
abstract class Adapter implements Contract
{
    /**
     * The key.
     *
     * @var string
     */
    protected string $key;

    /**
     * Adapter constructor.
     *
     * @param array<string, mixed> $config The config
     */
    public function __construct(protected array $config)
    {
        $key = $config['key'];

        if (! is_string($key)) {
            throw new InvalidArgumentException('Key must be a string');
        }

        $this->key = $key;
    }
}
