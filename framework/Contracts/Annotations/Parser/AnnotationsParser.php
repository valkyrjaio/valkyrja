<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Annotations\Parser;

/**
 * Interface AnnotationsParser
 *
 * @package Valkyrja\Contracts\Annotations\Parser
 *
 * @author  Melech Mizrachi
 */
interface AnnotationsParser
{
    /**
     * Get annotations from a given string.
     *
     * @param string $docString The doc string
     *
     * @return array
     */
    public function getAnnotations(string $docString): array;
}
