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
