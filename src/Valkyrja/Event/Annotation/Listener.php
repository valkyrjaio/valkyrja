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

namespace Valkyrja\Event\Annotation;

use Valkyrja\Annotation\Annotation;
use Valkyrja\Event\Listener as ListenerModel;

/**
 * Interface Listener.
 *
 * @author Melech Mizrachi
 */
interface Listener extends Annotation, ListenerModel
{
}
