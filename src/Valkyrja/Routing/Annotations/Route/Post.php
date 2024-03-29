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

namespace Valkyrja\Routing\Annotations\Route;

use Valkyrja\Http\Constants\RequestMethod;
use Valkyrja\Routing\Annotations\Route;

/**
 * Class Post.
 *
 * @author Melech Mizrachi
 */
class Post extends Route
{
    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->methods = [
            RequestMethod::POST,
        ];
    }
}
