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

namespace Valkyrja\Event\Annotation\Enums;

use Valkyrja\Enum\Enums\Enum;
use Valkyrja\Event\Annotation\Models\Listener;

/**
 * Enum AnnotationClass.
 *
 * @author Melech Mizrachi
 */
final class AnnotationClass extends Enum
{
    public const LISTENER = Listener::class;
}
