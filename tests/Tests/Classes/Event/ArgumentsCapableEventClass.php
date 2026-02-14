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

namespace Valkyrja\Tests\Classes\Event;

use Valkyrja\Event\Contract\ArgumentsCapableEventContract;

/**
 * Class to test events with arguments for unit testing.
 */
final class ArgumentsCapableEventClass implements ArgumentsCapableEventContract
{
    /** @var array<array-key, mixed> */
    private array $arguments = [];

    /**
     * @inheritDoc
     */
    public function setArguments(array $arguments): static
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
