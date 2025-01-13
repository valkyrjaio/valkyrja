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

use App\Http\Controllers\HomeController;
use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Dispatcher\Model\Dispatch;
use Valkyrja\Http\Message\Header\Value\Cookie;

require './vendor/autoload.php';

session_write_close();

$container     = new Container();
$dispatcher    = new Dispatcher($container);
$classDispatch = new Dispatch();
$classDispatch->setClass(Cookie::class)->setArguments(['name']);

$result = $dispatcher->dispatch($classDispatch);

@['method' => $method, 'property' => $property, 'class' => $class] = ['method' => 'test', 'class' => 'foo'];

var_dump($result, $method, $property, $class);

class Test
{
    public function __construct(
        public string $fire,
        public string $boo,
    ) {
    }

    public static function test()
    {
    }
}

function callsACallable(callable $test)
{
    var_dump($test, is_array($test));
}

var_dump(new Test(...['fire' => 'test', 'boo' => 'fight']), (new ReflectionFunction('\\Valkyrja\\app'))->getName(), callsACallable([Test::class, 'test']));

$headerRegex = <<<regex
    /
    \s*
    (.[^:]*)
    \s*
    :?
    \s*
    (.*)
    \s*
    /x
    regex;

$valueRegex = <<<regex
    /
    \s*
    (.[^,]*)
    \s*
    ,?
    \s*
    /x
    regex;

preg_match_all($headerRegex, 'test:value,foo,bar,value=test;cats', $matches);
preg_match_all($valueRegex, 'value,foo,bar,value=test;cats', $matches2);

$classString = Some\Namespace\That\Does\Not\Exist::class;
var_dump($matches, $matches2, new stdClass() instanceof $classString);

function callableTest(callable|array $test): void
{
}

callableTest([HomeController::class, 'welcome']);
callableTest([Cookie::class, '__toString']);

// $test = ["", "", ""];
// echo implode(",", array_filter($test));
//
// $cookie = new Cookie(
//     'name',
//     'value',
//     1,
//     '/',
//     'https://domain.com',
//     true,
//     false,
//     true,
//     SameSite::LAX,
//     false
// );
//
// echo $cookie->__toString();
//
// $test = fopen('php://temp', 'w+b');
