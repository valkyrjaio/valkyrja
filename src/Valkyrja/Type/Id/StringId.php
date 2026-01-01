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

use JsonException;
use Override;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\Id\Contract\StringIdContract as Contract;
use Valkyrja\Type\Throwable\Exception\InvalidArgumentException;

use function is_float;
use function is_int;
use function is_string;

/**
 * Class StringId.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<string>
 */
class StringId extends Type implements Contract
{
    public function __construct(string $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public static function fromValue(mixed $value): static
    {
        return match (true) {
            is_string($value) => new static($value),
            is_int($value), is_float($value) => new static((string) $value),
            default           => throw new InvalidArgumentException('Unsupported value provided'),
        };
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): string
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): string
    {
        return $this->asValue();
    }
}
