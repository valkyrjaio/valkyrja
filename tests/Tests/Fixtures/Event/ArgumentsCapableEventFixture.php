<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Event;

use Override;
use Valkyrja\Event\Contract\ArgumentsCapableEventContract;

/**
 * Class to test events with arguments for unit testing.
 */
final class ArgumentsCapableEventFixture implements ArgumentsCapableEventContract
{
    /** @var array<array-key, mixed> */
    private array $arguments = [];

    /**
     * @inheritDoc
     */
    #[Override]
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
