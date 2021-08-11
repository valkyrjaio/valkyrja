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

namespace Valkyrja\Routing\Annotation\Models\Redirect\Permanent;

use Valkyrja\Http\Constants\StatusCode;
use Valkyrja\Routing\Annotation\Models\Redirect\Options as ParentClass;

/**
 * Class Options.
 *
 * @author Melech Mizrachi
 */
class Options extends ParentClass
{
    /**
     * Options constructor.
     */
    public function __construct()
    {
        $this->code = StatusCode::MOVED_PERMANENTLY;

        parent::__construct();
    }
}
