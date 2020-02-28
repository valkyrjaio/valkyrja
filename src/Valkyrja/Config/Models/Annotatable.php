<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Models;

/**
 * Trait Annotatable.
 *
 * @author Melech Mizrachi
 */
trait Annotatable
{
    public bool      $useAnnotations                  = false;
    public bool      $useAnnotationsExclusively       = false;

    protected string $envUseAnnotationsKey            = '';
    protected string $envUseAnnotationsExclusivelyKey = '';

    /**
     * Set annotations config.
     *
     * @return void
     */
    protected function setAnnotationsConfig(): void
    {
        $this->useAnnotations            = (bool) env($this->envUseAnnotationsKey, $this->useAnnotations);
        $this->useAnnotationsExclusively = (bool) env(
            $this->envUseAnnotationsExclusivelyKey,
            $this->useAnnotationsExclusively
        );
    }
}
