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

namespace Valkyrja\Type\Json;

use JsonException;
use Valkyrja\Type\BuiltIn\Support\Obj as Helper;
use Valkyrja\Type\Json\Contract\JsonObject as Contract;
use Valkyrja\Type\Type;

use function is_string;

/**
 * Class JsonObject.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<object>
 *
 * @phpstan-consistent-constructor
 *   Will be overridden if need be
 */
class JsonObject extends Type implements Contract
{
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
            return new static(Helper::fromString($value));
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
        return Helper::toString($this->subject);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function modify(callable $closure): static
    {
        return static::fromValue($closure(clone $this->subject));
    }
}
