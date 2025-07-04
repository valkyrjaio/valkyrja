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

namespace Valkyrja\Jwt\Provider;

use OpenSSLAsymmetricKey;
use OpenSSLCertificate;
use Valkyrja\Application\Env;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Jwt\Contract\Jwt;
use Valkyrja\Jwt\Enum\Algorithm;
use Valkyrja\Jwt\FirebaseJwt;
use Valkyrja\Jwt\NullJwt;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            Jwt::class         => [self::class, 'publishJwt'],
            FirebaseJwt::class => [self::class, 'publishFirebaseJwt'],
            NullJwt::class     => [self::class, 'publishNullJwt'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Jwt::class,
            FirebaseJwt::class,
            NullJwt::class,
        ];
    }

    /**
     * Publish the jwt service.
     */
    public static function publishJwt(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<Jwt> $default */
        $default = $env::JWT_DEFAULT;

        $container->setSingleton(
            Jwt::class,
            $container->getSingleton($default),
        );
    }

    /**
     * Publish the jwt service.
     */
    public static function publishFirebaseJwt(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var Algorithm $algorithm */
        $algorithm = $env::JWT_ALGORITHM;

        /** @var OpenSSLAsymmetricKey|OpenSSLCertificate|string $encodeKey */
        $encodeKey = match ($algorithm) {
            Algorithm::HS256, Algorithm::HS384, Algorithm::HS512 => $env::JWT_HS_KEY,
            Algorithm::RS256, Algorithm::RS384, Algorithm::RS512 => $env::JWT_RS_PRIVATE_KEY,
            Algorithm::EdDSA                                     => $env::JWT_EDDSA_PRIVATE_KEY,
            default                                              => $env::APP_KEY,
        };

        /** @var OpenSSLAsymmetricKey|OpenSSLCertificate|string $decodeKey */
        $decodeKey = match ($algorithm) {
            Algorithm::HS256, Algorithm::HS384, Algorithm::HS512 => $env::JWT_HS_KEY,
            Algorithm::RS256, Algorithm::RS384, Algorithm::RS512 => $env::JWT_RS_PUBLIC_KEY,
            Algorithm::EdDSA                                     => $env::JWT_EDDSA_PUBLIC_KEY,
            default                                              => $env::APP_KEY,
        };

        $container->setSingleton(
            FirebaseJwt::class,
            new FirebaseJwt(
                encodeKey: $encodeKey,
                decodeKey: $decodeKey,
                algorithm: $algorithm,
            ),
        );
    }

    /**
     * Publish the jwt service.
     */
    public static function publishNullJwt(Container $container): void
    {
        $container->setSingleton(
            NullJwt::class,
            new NullJwt(),
        );
    }
}
