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

namespace Valkyrja\Tests\Classes\Attribute;

use Attribute;
use Valkyrja\Dispatch\Data\Contract\DispatchContract;

/**
 * Attribute child class used for unit testing.
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class AttributeClassChildClass extends AttributeClass
{
    public DispatchContract|null $dispatch = null;

    public mixed $default = null;

    public function __construct(
        int $counter,
        public string $test
    ) {
        parent::__construct($counter);
    }

    public function setDefault(mixed $default): void
    {
        $this->default = $default;
    }

    public function getDispatch(): DispatchContract|null
    {
        return $this->dispatch;
    }

    public function withDispatch(DispatchContract|null $dispatch): static
    {
        $this->dispatch = $dispatch;

        return $this;
    }
}
