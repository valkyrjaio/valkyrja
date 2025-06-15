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

namespace Valkyrja\Tests\Classes\Controller;

use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Routing\Attribute\Route;

/**
 * Controller class to test routes.
 *
 * @author Melech Mizrachi
 */
class ControllerClass
{
    #[Route(path: '/', name: 'welcome')]
    public function welcome(): Response
    {
        return \Valkyrja\Http\Message\Response\Response::create('welcome');
    }
}
