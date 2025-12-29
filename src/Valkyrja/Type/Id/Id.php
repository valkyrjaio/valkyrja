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

namespace Valkyrja\Type\Id;

use Override;
use Valkyrja\Type\Id\Contract\Id as Contract;
use Valkyrja\Type\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Type\Type;

use function is_float;
use function is_int;
use function is_string;

/**
 * Class Id.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<string|int>
 */
class Id extends Type implements Contract
{
    public function __construct(string|int $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function fromValue(mixed $value): static
    {
        return match (true) {
            is_string($value), is_int($value) => new static($value),
            is_float($value) => new static((string) $value),
            default          => throw new InvalidArgumentException('Unsupported value provided'),
        };
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): string|int
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): string|int
    {
        return $this->asValue();
    }
}
