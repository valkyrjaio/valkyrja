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

namespace Valkyrja\Type\Types;

use Closure;
use JsonException;
use Valkyrja\Type\Support\Obj as Helper;
use Valkyrja\Type\SerializedObject as Contract;

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
    protected const ALLOWED_CLASSES = [];

    public function __construct(object $subject)
    {
        parent::__construct($subject);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public static function fromValue(mixed $value): static
    {
        if (is_string($value)) {
            return new static(Helper::fromSerializedString($value, static::ALLOWED_CLASSES));
        }

        return new static((object) $value);
    }

    /**
     * @inheritDoc
     */
    public function asValue(): object
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function asFlatValue(): string
    {
        return Helper::toSerializedString($this->subject);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function modify(Closure $closure): static
    {
        return static::fromValue($closure(clone $this->subject));
    }
}
