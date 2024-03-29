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

namespace Valkyrja\Event\Enums;

use Valkyrja\Event\Annotations\Listener;
use Valkyrja\Type\Types\Enum;

/**
 * Enum AnnotationClass.
 *
 * @author Melech Mizrachi
 */
final class AnnotationClass extends Enum
{
    public const LISTENER = Listener::class;
}
