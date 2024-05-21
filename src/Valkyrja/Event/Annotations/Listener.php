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

namespace Valkyrja\Event\Annotations;

use Valkyrja\Annotation\Model\Annotatable;
use Valkyrja\Annotation\Model\Contract\Annotation;

/**
 * Class Listener.
 *
 * @author Melech Mizrachi
 */
class Listener extends \Valkyrja\Event\Models\Listener implements Annotation
{
    use Annotatable;
}
