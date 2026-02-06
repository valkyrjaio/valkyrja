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

namespace Valkyrja\Type\Object;

use JsonException;
use Override;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\Object\Contract\SerializedObjectContract;
use Valkyrja\Type\Object\Factory\ObjectFactory;

use function is_string;

/**
 * @extends Type<object>
 */
class SerializedObject extends Type implements SerializedObjectContract
{
    public function __construct(object $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @inheritDoc
     *
     * @param class-string[] $allowedClasses The allowed classes if the value is a serialized string
     *
     * @throws JsonException
     */
    #[Override]
    public static function fromValue(mixed $value, array $allowedClasses = []): static
    {
        if (is_string($value)) {
            return new static(ObjectFactory::fromSerializedString($value, $allowedClasses));
        }

        return new static((object) $value);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): object
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function asFlatValue(): string
    {
        return ObjectFactory::toSerializedString($this->subject);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function modify(callable $closure): static
    {
        return static::fromValue($closure(clone $this->subject));
    }
}
