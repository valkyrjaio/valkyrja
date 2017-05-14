<?php

namespace Valkyrja\Tests\Unit\Dispatcher;

use Valkyrja\Dispatcher\Dispatch;

/**
 * An invalid closure dispatch to test with.
 *
 * @author Melech Mizrachi
 */
class InvalidClosureDispatch extends Dispatch
{
    /**
     * InvalidClosureDispatch constructor.
     */
    public function __construct()
    {
        $this->closure = 'invalidClosure';
    }
}
