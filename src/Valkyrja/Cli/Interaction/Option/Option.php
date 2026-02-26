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

namespace Valkyrja\Cli\Interaction\Option;

use Override;
use Valkyrja\Cli\Interaction\Enum\OptionType;
use Valkyrja\Cli\Interaction\Option\Contract\OptionContract;
use Valkyrja\Cli\Interaction\Throwable\Exception\NoValueException;

class Option implements OptionContract
{
    /**
     * @param non-empty-string      $name  The name
     * @param non-empty-string|null $value [optional] The value
     */
    public function __construct(
        protected string $name,
        protected string|null $value = null,
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
        return $this->value !== null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getValue(): string
    {
        return $this->value
            ?? throw new NoValueException('No value exists');
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

        $new->value = null;

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
