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

namespace Valkyrja\Orm\Repository\Contract;

/**
 * Interface RelationshipRepository.
 *
 * @author Melech Mizrachi
 */
interface RelationshipRepository
{
    /**
     * Add relationships to include with the results.
     *
     * @param array|null $relationships [optional] The relationships to get
     *
     * @return static
     */
    public function withRelationships(array|null $relationships = null): static;

    /**
     * Add all relationships to include with the results.
     *
     * @return static
     */
    public function withAllRelationships(): static;

    /**
     * Remove relationships to include with the results.
     *
     * @return static
     */
    public function withoutRelationships(): static;
}
