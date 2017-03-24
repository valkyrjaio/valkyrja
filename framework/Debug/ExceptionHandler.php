<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Debug;

use ErrorException;
use Exception;
use Throwable;

use Valkyrja\Application;
use Valkyrja\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use Valkyrja\Contracts\Http\Exceptions\HttpException;
use Valkyrja\Contracts\Http\Response;
use Valkyrja\Http\Exceptions\HttpRedirectException;
use Valkyrja\Http\ResponseCode;

/**
 * Class ExceptionHandler
 *
 * @package Valkyrja\Debug
 *
 * @author  Melech Mizrachi
 */
class ExceptionHandler implements ExceptionHandlerContract
{
    /**
     * Charset.
     *
     * @var string
     */
    protected $charset = 'UTF-8';

    /**
     * File link format.
     *
     * @var string
     */
    protected $fileLinkFormat;

    /**
     * Whether to display errors.
     *
     * @var bool
     */
    protected $displayErrors;

    /**
     * ExceptionHandler constructor.
     *
     * @param bool $displayErrors [optional]
     */
    public function __construct(bool $displayErrors = false)
    {
        $this->displayErrors = $displayErrors;
        $this->fileLinkFormat = ini_get('xdebug.file_link_format') ?? get_cfg_var('xdebug.file_link_format');
    }

    /**
     * Handle an uncaught exception from the application.
     *
     * Note: Most exceptions can be handled via the try / catch block in
     * the HTTP and Console kernels. But, fatal error exceptions must
     * be handled differently since they are not normal exceptions.
     *
     * @param \Throwable $exception The exception that was captured
     *
     * @return void
     */
    public function handleException(Throwable $exception): void
    {
        if (! $exception instanceof Throwable) {
            $exception = new Exception($exception);
        }

        $this->sendExceptionResponse($exception);
    }

    /**
     * Handle the PHP shutdown event.
     *
     * @return void
     */
    public function handleShutdown(): void
    {
        $error = error_get_last();

        if (
            null !== $error &&
            in_array(
                $error['type'],
                [
                    E_ERROR,
                    E_CORE_ERROR,
                    E_COMPILE_ERROR,
                    E_PARSE,
                ],
                true
            )
        ) {
            $this->handleException($this->fatalExceptionFromError($error));
        }
    }

    /**
     * Create a new fatal exception instance from an error array.
     *
     * @param array $error The error array to use
     *
     * @return \Exception
     */
    protected function fatalExceptionFromError(array $error): Exception
    {
        return new ErrorException(
            $error['message'], 0, $error['type'], $error['file'], $error['line']
        );
    }

    /**
     * Get a response from an exception.
     *
     * @param \Throwable $exception The exception
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function getResponse($exception): Response
    {
        if (! $exception instanceof Throwable) {
            $exception = new Exception($exception);
        }

        $headers = [];
        $statusCode = ResponseCode::HTTP_INTERNAL_SERVER_ERROR;
        $content = $this->html($this->getContent($exception), $this->getStylesheet());

        if ($exception instanceof HttpException) {
            foreach ($exception->getHeaders() as $name => $value) {
                $headers[$name] = $value;
            }

            $statusCode = $exception->getStatusCode();
        }

        if ($exception instanceof HttpRedirectException) {
            $response = Application::app()->redirect(
                $exception->getUri(),
                $statusCode,
                $headers
            );
        }
        else {
            $response = Application::app()->response(
                $content,
                $statusCode,
                $headers
            );
        }

        $response->setCharset($this->charset);

        return $response;
    }

    /**
     * Send response.
     *
     * @param \Throwable $exception The exception
     *
     * @return void
     */
    public function sendResponse($exception): void
    {
        $this->getResponse($exception)->send();
    }

    /**
     * Send response.
     *
     * @param \Throwable $exception
     *
     * @return void
     */
    public function sendExceptionResponse($exception): void
    {
        if (! headers_sent()) {
            if ($exception instanceof HttpException) {
                header(sprintf('HTTP/1.0 %s', $exception->getStatusCode()));

                foreach ($exception->getHeaders() as $name => $value) {
                    header($name . ': ' . $value, false);
                }
            }

            header('Content-Type: text/html; charset=' . $this->charset);
        }

        echo $this->html($this->getContent($exception), $this->getStylesheet());
    }

    /**
     * Gets the HTML content associated with the given exception.
     *
     * @param \Throwable $exception A FlattenException instance
     *
     * @return string The content as a string
     */
    public function getContent(Throwable $exception): string
    {
        $title = 'Whoops, looks like something went wrong.';

        if ($exception instanceof HttpException && $exception->getStatusCode() === 404) {
            $title = 'Sorry, the page you are looking for could not be found.';
        }

        $content = '';

        if ($this->displayErrors) {

            try {
                $exceptions = [
                    $exception,
                ];
                $e = $exception;

                while ($e = $e->getPrevious()) {
                    $exceptions[] = $e;
                }

                $count = count($exceptions);
                $total = $count;

                /**
                 * @var int        $position
                 * @var \Throwable $e
                 */
                foreach ($exceptions as $position => $e) {
                    $ind = $count - $position;
                    $class = $this->formatClass(get_class($e));
                    $message = nl2br($this->escapeHtml($e->getMessage()));
                    $content .= sprintf(
                        <<<'EOF'
                                                <h2 class="block_exception clear_fix">
                            <span class="exception_counter">%d/%d</span>
                            <span class="exception_title">%s%s:</span>
                            <span class="exception_message">%s</span>
                        </h2>
                        <div class="block">
                            <ol class="traces list_exception">

EOF
                        ,
                        $ind,
                        $total,
                        $class,
                        $this->formatPath(
                            $e->getTrace()[0]['file'] ?? 'Unknown file',
                            $e->getTrace()[0]['line'] ?? 0
                        ),
                        $message
                    );

                    foreach ($e->getTrace() as $trace) {
                        $traceClass = $trace['class'] ?? '';
                        $traceArgs = $trace['args'] ?? [];
                        $traceType = $trace['type'] ?? '';
                        $traceFunction = $trace['function'] ?? '';

                        $content .= '       <li>';

                        if ($trace['function']) {
                            $content .= sprintf(
                                'at %s%s%s(%s)',
                                $this->formatClass($traceClass),
                                $traceType,
                                $traceFunction,
                                $this->formatArgs($traceArgs)
                            );
                        }

                        if (isset($trace['file'], $trace['line'])) {
                            $content .= $this->formatPath($trace['file'], $trace['line']);
                        }

                        $content .= "</li>\n";
                    }

                    $content .= "    </ol>\n</div>\n";
                }
            }
            catch (Exception $e) {
                // Something nasty happened and we cannot throw an exception anymore
                $title = sprintf(
                    'Exception thrown when handling an exception (%s: %s)',
                    get_class($e),
                    $this->escapeHtml($e->getMessage())
                );
            }
        }

        return <<<EOF
            <div id="sf-resetcontent" class="sf-reset">
                <h1>$title</h1>
                $content
            </div>
EOF;
    }

    /**
     * Gets the stylesheet associated with the given exception.
     *
     * @return string The stylesheet as a string
     */
    public function getStylesheet(): string
    {
        return <<<'EOF'
            .sf-reset { font: 11px Verdana, Arial, sans-serif; color: #333 }
            .sf-reset .clear { clear:both; height:0; font-size:0; line-height:0; }
            .sf-reset .clear_fix:after { display:block; height:0; clear:both; visibility:hidden; }
            .sf-reset .clear_fix { display:inline-block; }
            .sf-reset * html .clear_fix { height:1%; }
            .sf-reset .clear_fix { display:block; }
            .sf-reset, .sf-reset .block { margin: auto }
            .sf-reset abbr { border-bottom: 1px dotted #000; cursor: help; }
            .sf-reset p { font-size:14px; line-height:20px; color:#868686; padding-bottom:20px }
            .sf-reset strong { font-weight:bold; }
            .sf-reset a { color:#6c6159; cursor: default; }
            .sf-reset a img { border:none; }
            .sf-reset a:hover { text-decoration:underline; }
            .sf-reset em { font-style:italic; }
            .sf-reset h1, .sf-reset h2 { font: 20px Georgia, "Times New Roman", Times, serif }
            .sf-reset .exception_counter { background-color: #fff; color: #333; padding: 6px; float: left; margin-right: 10px; float: left; display: block; }
            .sf-reset .exception_title { margin-left: 3em; margin-bottom: 0.7em; display: block; }
            .sf-reset .exception_message { margin-left: 3em; display: block; }
            .sf-reset .traces li { font-size:12px; padding: 2px 4px; list-style-type:decimal; margin-left:20px; }
            .sf-reset .block { background-color:#FFFFFF; padding:10px 28px; margin-bottom:20px;
                -webkit-border-bottom-right-radius: 16px;
                -webkit-border-bottom-left-radius: 16px;
                -moz-border-radius-bottomright: 16px;
                -moz-border-radius-bottomleft: 16px;
                border-bottom-right-radius: 16px;
                border-bottom-left-radius: 16px;
                border-bottom:1px solid #ccc;
                border-right:1px solid #ccc;
                border-left:1px solid #ccc;
            }
            .sf-reset .block_exception { background-color:#ddd; color: #333; padding:20px;
                -webkit-border-top-left-radius: 16px;
                -webkit-border-top-right-radius: 16px;
                -moz-border-radius-topleft: 16px;
                -moz-border-radius-topright: 16px;
                border-top-left-radius: 16px;
                border-top-right-radius: 16px;
                border-top:1px solid #ccc;
                border-right:1px solid #ccc;
                border-left:1px solid #ccc;
                overflow: hidden;
                word-wrap: break-word;
            }
            .sf-reset a { background:none; color:#868686; text-decoration:none; }
            .sf-reset a:hover { background:none; color:#313131; text-decoration:underline; }
            .sf-reset ol { padding: 10px 0; }
            .sf-reset h1 { background-color:#FFFFFF; padding: 15px 28px; margin-bottom: 20px;
                -webkit-border-radius: 10px;
                -moz-border-radius: 10px;
                border-radius: 10px;
                border: 1px solid #ccc;
            }
EOF;
    }

    /**
     * Decorate the html response.
     *
     * @param string $content
     * @param string $css
     *
     * @return string
     */
    public function html(string $content, string $css): string
    {
        return <<<EOF
            <!DOCTYPE html>
            <html>
                <head>
                    <meta charset="{$this->charset}" />
                    <meta name="robots" content="noindex,nofollow" />
                    <style>
                        /* 
                            Copyright (c) 2010, Yahoo! Inc. All rights reserved. 
                            Code licensed under the BSD License: http://developer.yahoo.com/yui/license.html 
                        */
                        html{color:#000;background:#FFF;}
                        body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,
                        pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td{margin:0;padding:0;}
                        table{border-collapse:collapse;border-spacing:0;}
                        fieldset,img{border:0;}
                        address,caption,cite,code,dfn,em,strong,th,var{font-style:normal;font-weight:normal;}
                        li{list-style:none;}caption,th{text-align:left;}
                        h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal;}
                        q:before,q:after{content:'';}
                        abbr,acronym{border:0;font-variant:normal;}
                        sup{vertical-align:text-top;}
                        sub{vertical-align:text-bottom;}
                        input,textarea,select{font-family:inherit;font-size:inherit;font-weight:inherit;}
                        input,textarea,select{*font-size:100%;}
                        legend{color:#000;}
            
                        html { background: #eee; padding: 10px }
                        img { border: 0; }
                        #sf-resetcontent { width:970px; margin:0 auto; }
                        $css
                    </style>
                </head>
                <body>
                    $content
                </body>
            </html>
EOF;
    }

    /**
     * Format class.
     *
     * @param string $class
     *
     * @return string
     */
    protected function formatClass(string $class): string
    {
        $parts = explode('\\', $class);

        return sprintf('<abbr title="%s">%s</abbr>', $class, array_pop($parts));
    }

    /**
     * Format path.
     *
     * @param string $path
     * @param int    $line
     *
     * @return string
     */
    protected function formatPath(string $path, int $line): string
    {
        $path = $this->escapeHtml($path);
        $file = preg_match('#[^/\\\\]*$#', $path, $file)
            ? $file[0]
            : $path;

        if ($linkFormat = $this->fileLinkFormat) {
            $link = strtr($this->escapeHtml($linkFormat), ['%f' => $path, '%l' => $line]);

            return sprintf(
                ' in <a href="%s" title="Go to source">%s line %d</a>',
                $link,
                $file,
                $line
            );
        }

        return sprintf(
            ' in <a title="%s line %3$d" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">%s line %d</a>',
            $path,
            $file,
            $line
        );
    }

    /**
     * Formats an array as a string.
     *
     * @param array $args The argument array
     *
     * @return string
     */
    protected function formatArgs(array $args): string
    {
        $result = [];

        foreach ($args as $key => $item) {
            if (is_object($item)) {
                $formattedValue = sprintf('<em>object</em>(%s)', $this->formatClass(get_class($item)));
            }
            else if (is_array($item)) {
                $formattedValue = sprintf('<em>array</em>(%s)', $this->formatArgs($item));
            }
            else if (is_string($item)) {
                $formattedValue = sprintf("'%s'", $this->escapeHtml($item));
            }
            else if (null === $item) {
                $formattedValue = '<em>null</em>';
            }
            else if (is_bool($item)) {
                $formattedValue = '<em>' . strtolower(var_export($item, true)) . '</em>';
            }
            else if (is_resource($item)) {
                $formattedValue = '<em>resource</em>';
            }
            else {
                $formattedValue = str_replace("\n", '', var_export($this->escapeHtml((string) $item), true));
            }

            $result[] = is_int($key)
                ? $formattedValue
                : sprintf("'%s' => %s", $key, $formattedValue);
        }

        return implode(', ', $result);
    }

    /**
     * Escape HTML.
     *
     * @param string $str
     *
     * @return string
     */
    protected function escapeHtml(string $str): string
    {
        return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, $this->charset);
    }
}
