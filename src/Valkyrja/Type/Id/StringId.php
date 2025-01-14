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

use Valkyrja\Type\Id\Contract\StringId as Contract;
use Valkyrja\Type\Type;

/**
 * Class StringId.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<string>
 *
 * @phpstan-consistent-constructor
 *   Will be overridden if need be
 */
class StringId extends Type implements Contract
{
    public function __construct(string $subject)
    {
        parent::__construct($subject);
    }

    /**
     * @inheritDoc
     */
    public static function fromValue(mixed $value): static
    {
        return new static((string) $value);
    }

    /**
     * @inheritDoc
     */
    public function asValue(): string
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    public function asFlatValue(): string
    {
        return $this->asValue();
    }
}
