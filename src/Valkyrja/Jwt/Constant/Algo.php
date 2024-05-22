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

namespace Valkyrja\Jwt\Constant;

/**
 * Constant Algo.
 *
 * @author Melech Mizrachi
 */
final class Algo
{
    public const HS256 = 'HS256';
    public const HS384 = 'HS384';
    public const HS512 = 'HS512';

    public const PS256 = 'PS256';
    public const PS384 = 'PS384';
    public const PS512 = 'PS512';

    public const RS256 = 'RS256';
    public const RS384 = 'RS384';
    public const RS512 = 'RS512';

    public const ES256  = 'ES256';
    public const ES256K = 'ES256K';
    public const ES384  = 'ES384';
    public const ES512  = 'ES512';

    public const EDDSA = 'EdDSA';
}
