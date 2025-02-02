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

use JsonException;
use Valkyrja\Test\Assert\Contract\Compare as Contract;
use Valkyrja\Test\Exception\AssertFailureException;
use Valkyrja\Type\BuiltIn\Support\Str as StrSupport;

/**
 * Class Compare.
 *
 * @author Melech Mizrachi
 */
class Compare extends Asserter implements Contract
{
    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function equals(mixed $expected, mixed $actual): void
    {
        $this->assertions[] = 'equals';

        if ($expected === $actual) {
            $this->successes[] = 'equals';

            return;
        }

        $this->errors[] = new AssertFailureException(
            sprintf(
                'Failed asserting that expected %s matches actual %s',
                StrSupport::fromMixed($expected),
                StrSupport::fromMixed($actual)
            )
        );
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function notEquals(mixed $unexpected, mixed $actual): void
    {
        $this->assertions[] = 'notequals';

        if ($unexpected !== $actual) {
            $this->successes[] = 'notequals';

            return;
        }

        $this->errors[] = new AssertFailureException(
            sprintf(
                'Failed asserting that unexpected %s does not match actual %s',
                StrSupport::fromMixed($unexpected),
                StrSupport::fromMixed($actual)
            )
        );
    }
}
