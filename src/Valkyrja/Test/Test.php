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

use Closure;
use Throwable;
use Valkyrja\Test\Assert\Assert;
use Valkyrja\Test\Contract\Test as Contract;
use Valkyrja\Test\Output\Output;
use Valkyrja\Test\Output\Results\Results;

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
        protected Assert $assert = new \Valkyrja\Test\Assert\Asserters\Assert(),
        protected bool $shouldSkip = false,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getAssert(): Assert
    {
        return $this->assert;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name ?? '';
    }

    /**
     * @inheritDoc
     */
    public function shouldSkip(): bool
    {
        return $this->shouldSkip;
    }

    /**
     * @inheritDoc
     */
    public function run(Closure $closure, array $data = []): void
    {
        $assert    = $this->assert;
        $exception = null;

        try {
            $closure($assert, ...$data);
        } catch (Throwable $exception) {
        }

        $assert->exceptions()->verify($exception);

        $this->output?->full(new Results([$this]));
    }
}
