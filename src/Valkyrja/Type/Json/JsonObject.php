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
use Override;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\BuiltIn\Support\Obj as Helper;
use Valkyrja\Type\Json\Contract\JsonObjectContract as Contract;

use function is_string;

/**
 * Class JsonObject.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<object>
 */
class JsonObject extends Type implements Contract
{
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
            return new static(Helper::fromString($value));
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
        return Helper::toString($this->subject);
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
