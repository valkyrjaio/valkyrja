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
use Valkyrja\Type\BuiltIn\Enum\Support\Enum;

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
     * @var Asserter[]
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
        return $this->__call(AsserterName::compare->name, []);
    }

    /**
     * @inheritDoc
     */
    public function exceptions(): Exceptions
    {
        return $this->__call(AsserterName::exceptions->name, []);
    }

    /**
     * @inheritDoc
     */
    public function string(): Str
    {
        return $this->__call(AsserterName::string->name, []);
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
        return $this->getAllAsserterResults(ResultType::assertions);
    }

    /**
     * @inheritDoc
     */
    public function getErrors(): array
    {
        return $this->getAllAsserterResults(ResultType::errors);
    }

    /**
     * @inheritDoc
     */
    public function getSuccesses(): array
    {
        return $this->getAllAsserterResults(ResultType::successes);
    }

    /**
     * @inheritDoc
     */
    public function getWarnings(): array
    {
        return $this->getAllAsserterResults(ResultType::warnings);
    }

    /**
     * @inheritDoc
     */
    public function __get(string $name): mixed
    {
        if (Enum::isValidName(ResultType::class, $name)) {
            return match ($name) {
                ResultType::assertions->name => $this->getAssertions(),
                ResultType::errors->name     => $this->getErrors(),
                ResultType::successes->name  => $this->getSuccesses(),
                ResultType::warnings->name   => $this->getWarnings(),
            };
        }

        return $this->__call($name, []);
    }

    /**
     * @inheritDoc
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->asserterInstances[$name]
            ??= new $this->asserters[$name]();
    }

    /**
     * Get all the asserters' results by type.
     */
    protected function getAllAsserterResults(ResultType $type): array
    {
        $results = $this->{$type->name};

        foreach ($this->asserterInstances as $asserter) {
            $results[] = $asserter->{$type->name};
        }

        return array_merge(...$results);
    }
}
