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

/*
 *-------------------------------------------------------------------------
 * Container Configuration
 *-------------------------------------------------------------------------
 *
 * The container is the go to place for any type of service the
 * application may need when it is running. All configurations
 * necessary to make it run correctly can be found here.
 *
 */

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;

return [
    /*
     *-------------------------------------------------------------------------
     * Container Service Providers
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::PROVIDERS                   => env(
        EnvKey::CONTAINER_PROVIDERS,
        [
            Valkyrja\Annotation\NativeAnnotationsParser::class,
            Valkyrja\Annotation\NativeAnnotations::class,
            Valkyrja\Client\GuzzleClient::class,
            Valkyrja\Console\NativeConsole::class,
            Valkyrja\Console\NativeKernel::class,
            Valkyrja\Console\Input\NativeInput::class,
            Valkyrja\Console\Output\NativeOutput::class,
            Valkyrja\Console\Output\NativeOutputFormatter::class,
            Valkyrja\Console\Annotations\NativeCommandAnnotations::class,
            Valkyrja\Container\Annotations\NativeContainerAnnotations::class,
            Valkyrja\Crypt\SodiumCrypt::class,
            Valkyrja\Event\Annotations\NativeListenerAnnotations::class,
            Valkyrja\Filesystem\FlyFilesystem::class,
            Valkyrja\Http\NativeKernel::class,
            Valkyrja\Http\NativeRequest::class,
            Valkyrja\Http\NativeJsonResponse::class,
            Valkyrja\Http\NativeRedirectResponse::class,
            Valkyrja\Http\NativeResponse::class,
            Valkyrja\Http\NativeResponseBuilder::class,
            Valkyrja\Logger\Providers\LoggerServiceProvider::class,
            Valkyrja\Mail\PHPMailerMail::class,
            Valkyrja\ORM\PDOEntityManager::class,
            Valkyrja\Path\NativePathGenerator::class,
            Valkyrja\Path\NativePathParser::class,
            Valkyrja\Routing\NativeRouter::class,
            Valkyrja\Routing\Annotations\NativeRouteAnnotations::class,
            Valkyrja\Session\NativeSession::class,
            Valkyrja\View\PhpView::class,
        ]
    ),

    /*
     *-------------------------------------------------------------------------
     * Container Dev Service Providers
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::DEV_PROVIDERS               => env(EnvKey::CONTAINER_DEV_PROVIDERS, []),

    /*
     *-------------------------------------------------------------------------
     * Container Use Annotations
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::USE_ANNOTATIONS             => env(EnvKey::CONTAINER_USE_ANNOTATIONS, false),

    /*
     *-------------------------------------------------------------------------
     * Container Use Annotations Exclusively
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::USE_ANNOTATIONS_EXCLUSIVELY => env(EnvKey::CONTAINER_USE_ANNOTATIONS_EXCLUSIVELY, false),

    /*
     *-------------------------------------------------------------------------
     * Container Annotated Services
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::SERVICES                    => env(EnvKey::CONTAINER_SERVICES, []),

    /*
     *-------------------------------------------------------------------------
     * Container Annotated Context Services
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::CONTEXT_SERVICES            => env(EnvKey::CONTAINER_CONTEXT_SERVICES, []),

    /*
     *-------------------------------------------------------------------------
     * Container Bootstrap File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::FILE_PATH                   => env(EnvKey::CONTAINER_FILE_PATH, bootstrapPath('container.php')),

    /*
     *-------------------------------------------------------------------------
     * Container Cache File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::CACHE_FILE_PATH             => env(EnvKey::CONTAINER_CACHE_FILE_PATH, cachePath('container.php')),

    /*
     *-------------------------------------------------------------------------
     * Container Use Cache File
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    CKP::USE_CACHE                   => env(EnvKey::CONTAINER_USE_CACHE_FILE, false),
];
