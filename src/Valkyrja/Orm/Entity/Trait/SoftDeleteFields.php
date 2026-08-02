<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Entity\Trait;

trait SoftDeleteFields
{
    /**
     * The date the entity was soft deleted.
     *
     * @var string|null
     */
    public string|null $deleted_at = null;
}
