<?php
/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Based off work by Taylor Otwell for Illuminate/routing/ResponseFactory.php
 */
namespace Valkyrja\Http;

use Valkyrja\Contracts\Http\Response as ResponseContract;
use Valkyrja\Contracts\Http\ResponseBuilder as ResponseBuilderContract;
use Valkyrja\Contracts\View\View;

/**
 * Class ResponseBuilder
 *
 * @package Valkyrja\Http
 *
 * @author  Melech Mizrachi
 */
class ResponseBuilder implements ResponseBuilderContract
{
    /**
     * The view factory instance.
     *
     * @var View
     */
    protected $view;

    /**
     * The response factory instance.
     *
     * @var ResponseContract
     */
    protected $response;

    /**
     * @inheritdoc
     */
    public function __construct(ResponseContract $response, View $view)
    {
        $this->response = $response;
        $this->view = $view;
    }

    /**
     * @inheritdoc
     */
    public function make($content = '', $status = 200, array $headers = [])
    {
        return $this->response->create($content, $status, $headers);
    }

    /**
     * @inheritdoc
     */
    public function view($view, array $data = [], $status = 200, array $headers = [])
    {
        $content = $this->view->make($view, $data)
                              ->render();

        return $this->make($content, $status, $headers);
    }

    /**
     * @inheritdoc
     */
    public function json($data = [], $status = 200, array $headers = [], $options = 0)
    {
        //
    }

    /**
     * @inheritdoc
     */
    public function jsonp($callback, $data = [], $status = 200, array $headers = [], $options = 0)
    {
        //
    }

    /**
     * @inheritdoc
     */
    public function redirectTo($path, array $parameters = [], $status = 302, array $headers = [])
    {
        //
    }

    /**
     * @inheritdoc
     */
    public function redirectToRoute($route, array $parameters = [], $status = 302, array $headers = [])
    {
        //
    }
}
