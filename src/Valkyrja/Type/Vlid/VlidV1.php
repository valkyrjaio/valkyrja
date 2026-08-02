<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Vlid;

use Valkyrja\Type\Vlid\Contract\VlidV1Contract;
use Valkyrja\Type\Vlid\Factory\VlidV1Factory;

class VlidV1 extends Vlid implements VlidV1Contract
{
    public function __construct(string|null $subject = null)
    {
        if ($subject !== null) {
            VlidV1Factory::validate($subject);
        }

        parent::__construct($subject ?? VlidV1Factory::generate());
    }
}
