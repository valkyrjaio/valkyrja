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

namespace Valkyrja\Config\Models;

use function Valkyrja\env;

/**
 * Class Annotatable.
 *
 * @author Melech Mizrachi
 */
class Annotatable extends Model
{
    /**
     * The flag to enable annotations.
     *
     * @var bool
     */
    public bool $useAnnotations;

    /**
     * The flag to use annotations exclusively (forgoing filePath).
     *
     * @var bool
     */
    public bool $useAnnotationsExclusively;

    /**
     * The use annotations flag env key.
     *
     * @var string
     */
    protected string $envUseAnnotationsKey;

    /**
     * The use annotations exclusively env key.
     *
     * @var string
     */
    protected string $envUseAnnotationsExclusivelyKey;

    /**
     * Set annotations config.
     *
     * @return void
     */
    protected function setAnnotationsConfig(): void
    {
        $this->setUseAnnotations();
        $this->setUseAnnotationsExclusively();
    }

    /**
     * Set the flag to use annotations.
     *
     * @param bool $useAnnotations [optional] The flag to use annotations
     *
     * @return void
     */
    protected function setUseAnnotations(bool $useAnnotations = false): void
    {
        $this->useAnnotations = (bool) env($this->envUseAnnotationsKey, $useAnnotations);
    }

    /**
     * Set the flag to use annotations exclusively.
     *
     * @param bool $useAnnotationsExclusively [optional] Flag to use annotations exclusively
     *
     * @return void
     */
    protected function setUseAnnotationsExclusively(bool $useAnnotationsExclusively = false): void
    {
        $this->useAnnotationsExclusively = (bool) env(
            $this->envUseAnnotationsExclusivelyKey,
            $useAnnotationsExclusively
        );
    }

    /**
     * Set the use annotations flag env key.
     *
     * @param string $envKey The use annotations flag env key.
     *
     * @return void
     */
    protected function setUseAnnotationsEnvKey(string $envKey): void
    {
        $this->envUseAnnotationsKey = $envKey;
    }

    /**
     * Set the use annotations exclusively flag env key.
     *
     * @param string $envKey The use annotations exclusively flag env key.
     *
     * @return void
     */
    protected function setUseAnnotationsExclusivelyEnvKey(string $envKey): void
    {
        $this->envUseAnnotationsExclusivelyKey = $envKey;
    }
}
