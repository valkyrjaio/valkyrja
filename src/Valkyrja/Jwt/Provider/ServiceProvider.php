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
use Override;
use Valkyrja\Application\Env\Env;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Jwt\Enum\Algorithm;
use Valkyrja\Jwt\Manager\Contract\JwtContract;
use Valkyrja\Jwt\Manager\FirebaseJwt;
use Valkyrja\Jwt\Manager\NullJwt;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            JwtContract::class => [self::class, 'publishJwt'],
            FirebaseJwt::class => [self::class, 'publishFirebaseJwt'],
            NullJwt::class     => [self::class, 'publishNullJwt'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            JwtContract::class,
            FirebaseJwt::class,
            NullJwt::class,
        ];
    }

    /**
     * Publish the jwt service.
     */
    public static function publishJwt(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<JwtContract> $default */
        $default = $env::JWT_DEFAULT
            ?? FirebaseJwt::class;

        $container->setSingleton(
            JwtContract::class,
            $container->getSingleton($default),
        );
    }

    /**
     * Publish the jwt service.
     */
    public static function publishFirebaseJwt(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var Algorithm $algorithm */
        $algorithm = $env::JWT_ALGORITHM
            ?? Algorithm::HS256;

        /** @var OpenSSLAsymmetricKey|OpenSSLCertificate|string $encodeKey */
        $encodeKey = match ($algorithm) {
            Algorithm::HS256, Algorithm::HS384, Algorithm::HS512 => $env::JWT_HS_KEY ?? 'key',
            Algorithm::RS256, Algorithm::RS384, Algorithm::RS512 => $env::JWT_RS_PRIVATE_KEY ?? 'private-key',
            Algorithm::EdDSA => $env::JWT_EDDSA_PRIVATE_KEY ?? 'private-key',
            default          => $env::APP_KEY,
        };

        /** @var OpenSSLAsymmetricKey|OpenSSLCertificate|string $decodeKey */
        $decodeKey = match ($algorithm) {
            Algorithm::HS256, Algorithm::HS384, Algorithm::HS512 => $env::JWT_HS_KEY ?? 'key',
            Algorithm::RS256, Algorithm::RS384, Algorithm::RS512 => $env::JWT_RS_PUBLIC_KEY ?? 'public-key',
            Algorithm::EdDSA => $env::JWT_EDDSA_PUBLIC_KEY ?? 'public-key',
            default          => $env::APP_KEY,
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
    public static function publishNullJwt(ContainerContract $container): void
    {
        $container->setSingleton(
            NullJwt::class,
            new NullJwt(),
        );
    }
}
