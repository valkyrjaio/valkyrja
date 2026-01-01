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

namespace Valkyrja\Tests\Classes\Type;

use Valkyrja\Type\Abstract\Type as AbstractType;

/**
 * Type class to use to test abstract type.
 */
class TypeClass extends AbstractType
{
    public function __construct(mixed $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @inheritDoc
     */
    public static function fromValue(mixed $value): static
    {
        return new static($value);
    }

    public function asFlatValue(): string|int|float|bool|null
    {
        return $this->asValue();
    }
}
