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

namespace Valkyrja\Cli\Routing\Data;

use Override;
use Valkyrja\Cli\Interaction\Argument\Contract\ArgumentContract;
use Valkyrja\Cli\Routing\Data\Abstract\Parameter;
use Valkyrja\Cli\Routing\Data\Contract\ArgumentParameterContract;
use Valkyrja\Cli\Routing\Enum\ArgumentMode;
use Valkyrja\Cli\Routing\Enum\ArgumentValueMode;
use Valkyrja\Cli\Routing\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Type\Data\Cast;

use function count;

class ArgumentParameter extends Parameter implements ArgumentParameterContract
{
    /** @var ArgumentContract[] */
    protected array $arguments = [];

    /**
     * @param non-empty-string $name        The name
     * @param non-empty-string $description The description
     */
    public function __construct(
        string $name,
        string $description,
        Cast|null $cast = null,
        protected ArgumentMode $mode = ArgumentMode::OPTIONAL,
        protected ArgumentValueMode $valueMode = ArgumentValueMode::DEFAULT,
    ) {
        parent::__construct(
            name: $name,
            description: $description,
            cast: $cast
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getMode(): ArgumentMode
    {
        return $this->mode;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withMode(ArgumentMode $mode): static
    {
        $new = clone $this;

        $new->mode = $mode;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getValueMode(): ArgumentValueMode
    {
        return $this->valueMode;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withValueMode(ArgumentValueMode $valueMode): static
    {
        $new = clone $this;

        $new->valueMode = $valueMode;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withArguments(ArgumentContract ...$arguments): static
    {
        $new = clone $this;

        $new->arguments = $arguments;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedArguments(ArgumentContract ...$arguments): static
    {
        $new = clone $this;

        foreach ($arguments as $argument) {
            $new->arguments[] = $argument;
        }

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCastValues(): array
    {
        $values   = [];
        $cast     = $this->cast;
        $castType = $cast->type ?? null;

        foreach ($this->arguments as $argument) {
            if ($cast === null || $castType === null) {
                $values[] = $argument->getValue();

                continue;
            }

            $value = $castType::fromValue($argument->getValue());

            if ($cast->convert) {
                /** @psalm-suppress MixedAssignment */
                $values[] = $value->asValue();

                continue;
            }

            $values[] = $value;
        }

        return $values;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getFirstValue(): string|null
    {
        $firstItem = $this->arguments[0] ?? null;

        return $firstItem?->getValue();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function areValuesValid(): bool
    {
        return match (true) {
            $this->mode === ArgumentMode::REQUIRED          => $this->arguments !== [],
            $this->valueMode === ArgumentValueMode::DEFAULT => count($this->arguments) <= 1,
            default                                         => true,
        };
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function validateValues(): static
    {
        if (! $this->areValuesValid()) {
            throw new InvalidArgumentException("$this->name is required");
        }

        return $this;
    }
}
