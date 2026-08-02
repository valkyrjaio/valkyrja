<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Option;

use Override;
use Valkyrja\Cli\Interaction\Enum\OptionType;
use Valkyrja\Cli\Interaction\Option\Contract\OptionContract;

class Option implements OptionContract
{
    /**
     * @param non-empty-string $name The name
     */
    public function __construct(
        protected string $name,
        protected string $value = '',
        protected OptionType $type = OptionType::LONG,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withName(string $name): static
    {
        $new = clone $this;

        $new->name = $name;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasValue(): bool
    {
        return $this->value !== '';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withValue(string $value): static
    {
        $new = clone $this;

        $new->value = $value;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withoutValue(): static
    {
        $new = clone $this;

        $new->value = '';

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getType(): OptionType
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withType(OptionType $type): static
    {
        $new = clone $this;

        $new->type = $type;

        return $new;
    }
}
