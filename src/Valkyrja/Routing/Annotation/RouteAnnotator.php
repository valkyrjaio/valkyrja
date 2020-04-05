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

namespace Valkyrja\Routing\Annotation;

/**
 * Interface RouteAnnotations.
 *
 * @author Melech Mizrachi
 */
interface RouteAnnotator
{
    /**
     * Get routes.
     *
     * @param string ...$classes The classes
     *
     * @return array
     */
    public function getRoutes(string ...$classes): array;
}
