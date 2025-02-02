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

namespace Valkyrja\Test\Assert;

use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Test\Assert\Asserter as AbstractAsserter;
use Valkyrja\Test\Assert\Compare as CompareAsserter;
use Valkyrja\Test\Assert\Contract\Assert as Contract;
use Valkyrja\Test\Assert\Contract\Asserter;
use Valkyrja\Test\Assert\Contract\Compare;
use Valkyrja\Test\Assert\Contract\Exceptions;
use Valkyrja\Test\Assert\Contract\Str;
use Valkyrja\Test\Assert\Enum\AsserterName;
use Valkyrja\Test\Assert\Enum\ResultType;
use Valkyrja\Test\Assert\Exceptions as ExceptionsAsserter;
use Valkyrja\Test\Assert\Str as StrAsserter;

use function array_merge;

/**
 * Class Assert.
 *
 * @author Melech Mizrachi
 */
class Assert extends AbstractAsserter implements Contract
{
    /**
     * Asserter instances.
     *
     * @var array<string, Asserter>
     */
    protected array $asserterInstances = [];

    /**
     * @param array<string, class-string<Asserter>> $asserters
     */
    public function __construct(
        protected array $asserters = [],
    ) {
        $this->asserters = array_merge(
            [
                AsserterName::compare->name    => CompareAsserter::class,
                AsserterName::exceptions->name => ExceptionsAsserter::class,
                AsserterName::string->name     => StrAsserter::class,
            ],
            $asserters
        );
    }

    /**
     * @inheritDoc
     */
    public function compare(): Compare
    {
        $compare = $this->__call(AsserterName::compare->name, []);

        if (! $compare instanceof Compare) {
            throw new InvalidArgumentException('Expecting Str contract');
        }

        return $compare;
    }

    /**
     * @inheritDoc
     */
    public function exceptions(): Exceptions
    {
        $exceptions = $this->__call(AsserterName::exceptions->name, []);

        if (! $exceptions instanceof Exceptions) {
            throw new InvalidArgumentException('Expecting Str contract');
        }

        return $exceptions;
    }

    /**
     * @inheritDoc
     */
    public function string(): Str
    {
        $str = $this->__call(AsserterName::string->name, []);

        if (! $str instanceof Str) {
            throw new InvalidArgumentException('Expecting Str contract');
        }

        return $str;
    }

    /**
     * @inheritDoc
     */
    public function withAsserters(array $asserters): void
    {
        $this->asserters = array_merge($this->asserters, $asserters);
    }

    /**
     * @inheritDoc
     */
    public function getAssertions(): array
    {
        $assertions = [];

        foreach ($this->asserterInstances as $asserter) {
            $assertions[] = $asserter->getAssertions();
        }

        return array_merge(...$assertions);
    }

    /**
     * @inheritDoc
     */
    public function getErrors(): array
    {
        $assertions = [];

        foreach ($this->asserterInstances as $asserter) {
            $assertions[] = $asserter->getErrors();
        }

        return array_merge(...$assertions);
    }

    /**
     * @inheritDoc
     */
    public function getSuccesses(): array
    {
        $assertions = [];

        foreach ($this->asserterInstances as $asserter) {
            $assertions[] = $asserter->getSuccesses();
        }

        return array_merge(...$assertions);
    }

    /**
     * @inheritDoc
     */
    public function getWarnings(): array
    {
        $assertions = [];

        foreach ($this->asserterInstances as $asserter) {
            $assertions[] = $asserter->getWarnings();
        }

        return array_merge(...$assertions);
    }

    /**
     * @inheritDoc
     */
    public function __get(string $name): mixed
    {
        return match ($name) {
            ResultType::assertions->name => $this->getAssertions(),
            ResultType::errors->name => $this->getErrors(),
            ResultType::successes->name => $this->getSuccesses(),
            ResultType::warnings->name => $this->getWarnings(),
            default => $this->__call($name, []),
        };
    }

    /**
     * @inheritDoc
     */
    public function __call(string $name, array $arguments): Asserter
    {
        return $this->asserterInstances[$name]
            ??= new $this->asserters[$name]();
    }
}
