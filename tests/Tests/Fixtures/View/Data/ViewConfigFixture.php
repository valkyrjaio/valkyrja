<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\View\Data;

use Twig\Extension\ExtensionInterface;
use Valkyrja\Application\Data\Config;
use Valkyrja\View\Data\Contract\ViewConfigContract;
use Valkyrja\View\Data\Contract\ViewOrkaConfigContract;
use Valkyrja\View\Data\Contract\ViewPhpConfigContract;
use Valkyrja\View\Data\Contract\ViewTwigConfigContract;
use Valkyrja\View\Orka\Constant\OrkaReplacementCollection;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;
use Valkyrja\View\Renderer\Contract\RendererContract;
use Valkyrja\View\Renderer\OrkaRenderer;

/**
 * An application config that implements every view contract at once.
 *
 * The renderer contracts prefix each property with the renderer name, so one
 * class can carry the settings for several renderers without a name collision.
 */
final class ViewConfigFixture extends Config implements ViewConfigContract, ViewPhpConfigContract, ViewOrkaConfigContract, ViewTwigConfigContract
{
    /**
     * @param class-string<RendererContract>            $defaultRenderer
     * @param non-empty-string                          $phpPath
     * @param non-empty-string                          $phpFileExtension
     * @param array<string, string>                     $phpPaths
     * @param non-empty-string                          $orkaPath
     * @param non-empty-string                          $orkaFileExtension
     * @param array<non-empty-string, non-empty-string> $orkaPaths
     * @param class-string<ReplacementContract>[]       $orkaCoreReplacements
     * @param class-string<ReplacementContract>[]       $orkaReplacements
     * @param array<string, string>                     $twigPaths
     * @param class-string<ExtensionInterface>[]        $twigExtensions
     * @param non-empty-string                          $twigCompiledPath
     */
    public function __construct(
        public string $defaultRenderer = OrkaRenderer::class,
        public string $phpPath = '/storage',
        public string $phpFileExtension = '.test.phtml',
        public array $phpPaths = [],
        public string $orkaPath = '/storage',
        public string $orkaFileExtension = '.test.orka.phtml',
        public array $orkaPaths = [],
        public array $orkaCoreReplacements = OrkaReplacementCollection::CORE,
        public array $orkaReplacements = [],
        public array $twigPaths = [],
        public array $twigExtensions = [],
        public string $twigCompiledPath = '/storage',
    ) {
        parent::__construct();
    }
}
