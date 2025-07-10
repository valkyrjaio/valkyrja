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

namespace Valkyrja\Type\BuiltIn;

use JsonException;
use Override;
use Valkyrja\Type\BuiltIn\Contract\SerializedObject as Contract;
use Valkyrja\Type\BuiltIn\Support\Obj as Helper;
use Valkyrja\Type\Type;

use function is_string;

/**
 * Class SerializedObject.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<object>
 */
class SerializedObject extends Type implements Contract
{
    /**
     * Allowed classes for serialization of object type properties.
     *
     *  <code>
     *       [
     *           // An array of allowed classes for serialization for object types
     *           ClassName::class,
     *       ]
     *  </code>
     *
     * @return class-string[]
     */
    protected const array ALLOWED_CLASSES = [];

    public function __construct(object $subject)
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
        if (is_string($value)) {
            /** @var class-string[] $allowedClasses */
            $allowedClasses = static::ALLOWED_CLASSES;

            return new static(Helper::fromSerializedString($value, $allowedClasses));
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
        return Helper::toSerializedString($this->subject);
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
