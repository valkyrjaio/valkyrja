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

use Valkyrja\Cli\Interaction\Argument\Contract\Argument;
use Valkyrja\Cli\Routing\Data\Contract\ArgumentParameter as Contract;
use Valkyrja\Cli\Routing\Enum\ArgumentMode;
use Valkyrja\Cli\Routing\Exception\InvalidArgumentException;
use Valkyrja\Type\Data\Cast;

/**
 * Class ArgumentParameter.
 *
 * @author Melech Mizrachi
 */
class ArgumentParameter extends Parameter implements Contract
{
    /** @var Argument[] */
    protected array $arguments = [];

    public function __construct(
        string $name,
        string $description,
        ?Cast $cast = null,
        protected ArgumentMode $mode = ArgumentMode::OPTIONAL,
    ) {
        parent::__construct($name, $description, $cast);
    }

    /**
     * @inheritDoc
     */
    public function getMode(): ArgumentMode
    {
        return $this->mode;
    }

    /**
     * @inheritDoc
     */
    public function withMode(ArgumentMode $mode): static
    {
        $new = clone $this;

        $new->mode = $mode;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @inheritDoc
     */
    public function withArguments(Argument ...$arguments): static
    {
        $new = clone $this;

        $new->arguments = $arguments;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withAddedArguments(Argument ...$arguments): static
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
    public function areValuesValid(): bool
    {
        return match ($this->mode) {
            ArgumentMode::REQUIRED       => count($this->arguments) === 1,
            ArgumentMode::REQUIRED_ARRAY => $this->arguments !== [],
            default                      => true,
        };
    }

    /**
     * @inheritDoc
     */
    public function validateValues(): static
    {
        if (! $this->areValuesValid()) {
            throw new InvalidArgumentException("$this->name is required");
        }

        return $this;
    }
}
