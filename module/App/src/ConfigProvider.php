<?php

/**
 * The MIT License
 *
 * Copyright (c) 2016, Coding Matters, Inc. (Gab Amba <gamba@gabbydgab.com>)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace App;

use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Application;
use Zend\Expressive\Helper;
use Zend\Expressive\Router;

/**
 * Zend Expressive Config Provider
 */
final class ConfigProvider
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return $this->getConfig();
    }

    /**
     * Provides application-wide configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'dependencies'          => $this->getServiceConfig(),
            'middleware_pipeline'   => $this->getMiddlewareConfig(),
            'routes'                => $this->getRouteConfig()
        ];
    }

    /**
     * Provides application-wide services.
     * We recommend using fully-qualified class names whenever possible as service names
     *
     * @return array
     */
    public function getServiceConfig()
    {
        return [
            'invokables' => [
                Router\RouterInterface::class => Router\FastRouteRouter::class,
                Helper\ServerUrlHelper::class => Helper\ServerUrlHelper::class,
            ],
            'factories' => [
                Helper\ServerUrlMiddleware::class   => Helper\ServerUrlMiddlewareFactory::class,
                Helper\UrlHelperMiddleware::class   => Helper\UrlHelperMiddlewareFactory::class,
                Helper\UrlHelper::class             => Helper\UrlHelperFactory::class,
                Application::class                  => ApplicationFactory::class,
            ]
        ];
    }

    public function getRouteConfig()
    {
    }

    /**
     * An array of middleware to register. Each item is of the following specification:
     *
     * <code>
     * [
     *  Required:
     *      'middleware' => 'Name or array of names of middleware services and/or callables',
     *  Optional:
     *      'path'     => '/path/to/match', // string; literal path prefix to match
     *                                      // middleware will not execute if path does not match!
     *      'error'    => true, // boolean; true for error middleware
     *      'priority' => 1, // int; higher values == register early;
     *                      // lower/negative == register last;
     *                      // default is 1, if none is provided.
     * ]
     * </code>
     *
     * While the ApplicationFactory ignores the keys associated with specifications, they can be used to allow merging
     * related values defined in multiple configuration files/locations.
     *
     * This defines some conventional keys for middleware to execute early, routing middleware, and error middleware.
     *
     * @return array
     */
    public function getMiddlewareConfig()
    {
        return [
            /**
             * Add more middleware here that you want to execute on every request:
             * - bootstrapping
             * - pre-conditions
             * - modifications to outgoing responses
             */
            'always' => [
                'middleware' => [
                    Helper\ServerUrlMiddleware::class,
                ],
                'priority' => 10000,
            ],
            /**
             * Add more middleware here that needs to introspect the routing results; this might include:
             * - route-based authentication
             * - route-based validation
             * - etc.
             */
            'routing' => [
                'middleware' => [
                    ApplicationFactory::ROUTING_MIDDLEWARE,
                    Helper\UrlHelperMiddleware::class,
                    // Append here
                    ApplicationFactory::DISPATCH_MIDDLEWARE,
                ],
                'priority' => 1,
            ],

            'error' => [
                'middleware' => [
                    //Add more error handler middleware
                ],
                'error'    => true,
                'priority' => -10000,
            ],
        ];
    }
}
