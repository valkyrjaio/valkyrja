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

use Valkyrja\Type\BuiltIn\Contract\BoolT as Contract;
use Valkyrja\Type\Type;

/**
 * Class BoolT.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<bool>
 */
class BoolT extends Type implements Contract
{
    public function __construct(bool $subject)
    {
        parent::__construct($subject);
    }

    /**
     * @inheritDoc
     */
    public static function fromValue(mixed $value): static
    {
        return new static((bool) $value);
    }

    /**
     * @inheritDoc
     */
    public function asValue(): bool
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    public function asFlatValue(): bool
    {
        return $this->asValue();
    }
}
