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

namespace Valkyrja\Dispatch\Data;

use Override;
use Valkyrja\Dispatch\Data\Abstract\Dispatch;
use Valkyrja\Dispatch\Data\Contract\CallableDispatchContract as Contract;

/**
 * Class CallableDispatch.
 */
class CallableDispatch extends Dispatch implements Contract
{
    /** @var callable */
    protected $callable;

    /**
     * @param callable                                   $callable     The callable
     * @param array<non-empty-string, mixed>|null        $arguments    The arguments
     * @param array<non-empty-string, class-string>|null $dependencies The dependencies
     */
    public function __construct(
        callable $callable,
        protected array|null $arguments = null,
        protected array|null $dependencies = null,
    ) {
        $this->callable = $callable;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCallable(): callable
    {
        return $this->callable;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withCallable(callable $callable): static
    {
        $new = clone $this;

        $new->callable = $callable;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getArguments(): array|null
    {
        return $this->arguments;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withArguments(array|null $arguments = null): static
    {
        $new = clone $this;

        $new->arguments = $arguments;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDependencies(): array|null
    {
        return $this->dependencies;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withDependencies(array|null $dependencies = null): static
    {
        $new = clone $this;

        $new->dependencies = $dependencies;

        return $new;
    }
}
