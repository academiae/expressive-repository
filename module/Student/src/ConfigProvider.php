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

namespace Student;

/**
 * Zend Expressive Config Provider
 */
final class ConfigProvider
{
    public function __invoke()
    {
        return $this->getConfig();
    }

    public function getConfig()
    {
        return [
            'dependencies'          => $this->getServiceConfig(),
            'middleware_pipeline'   => $this->getMiddlewareConfig(),
            'routes'                => $this->getRouteConfig()
        ];
    }

    public function getServiceConfig()
    {
        return [
            'invokables'    => [
//                Entity\StudentPrototype::class  => Entity\StudentEntity::class
            ],
            'factories'     => [
                Repository\MasterListRepository::class  => Factory\Repository\MasterListRepositoryFactory::class,
                Repository\AlumniListRepository::class  => Factory\Repository\AlumniListRepositoryFactory::class,

                Controller\MasterListController::class => Factory\Controller\MasterListControllerFactory::class,
//                Action\MasterListAction::class => Factory\Action\MasterListActionFactory::class,
//                Action\AlumniListAction::class => Factory\Action\AlumniListActionFactory::class,
            ],
            'delegator'     => []
        ];
    }

    public function getRouteConfig()
    {
        return [
            [
                'name' => 'students',
                'path' => '/students[/{uuid:[0-9]+}]',
                'middleware' => [
                    Controller\MasterListController::class
                ]
            ],
//            [
//                'name' => 'students.alumni',
//                'path' => '/students/alumni',
//                'middleware' => Action\AlumniListAction::class,
//                'allowed_methods' => ['GET'],
//            ],
//            [
//                'name' => '',
//                'path' => '',
//                'middleware' => '',
//                'allowed_methods' => ['GET'],
//            ],
//            [
//                'name' => '',
//                'path' => '',
//                'middleware' => '',
//                'allowed_methods' => ['GET'],
//            ]
        ];
    }

    public function getMiddlewareConfig()
    {
        return [];
    }
}
