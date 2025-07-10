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

namespace Valkyrja\Test;

use Override;
use Throwable;
use Valkyrja\Test\Assert\Contract\Assert;
use Valkyrja\Test\Contract\Test as Contract;
use Valkyrja\Test\Output\Contract\Output;
use Valkyrja\Test\Result\Results;

/**
 * Class Test.
 *
 * @author Melech Mizrachi
 */
class Test implements Contract
{
    public function __construct(
        protected string|null $name = null,
        protected Output|null $output = null,
        protected Assert $assert = new \Valkyrja\Test\Assert\Assert(),
        protected bool $shouldSkip = false,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getAssert(): Assert
    {
        return $this->assert;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getName(): string
    {
        return $this->name ?? '';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function shouldSkip(): bool
    {
        return $this->shouldSkip;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function run(callable $callable, array $data = []): void
    {
        $assert    = $this->assert;
        $exception = null;

        try {
            $callable($assert, ...$data);
        } catch (Throwable $exception) {
        }

        $assert->exceptions()->verify($exception);

        $this->output?->full(new Results([$this]));
    }
}
