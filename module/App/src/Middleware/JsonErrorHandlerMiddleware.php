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

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router\RouteResult;

class JsonErrorHandlerMiddleware
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $out
     * @return JsonResponse
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $out = null)
    {
        $hasRoute = $request->getAttribute(RouteResult::class) !== null;
        $isNotFound = ! $hasRoute && ! isset($err);
        if ($isNotFound) {
            $responsePhrase = 'Not found';
            $status = 404;
        } else {
            $status = $response->getStatusCode();
            $responsePhrase = $status < 400 ? 'Internal Server Error' : $response->getReasonPhrase();
            $status = $status < 400 ? 500 : $status;
        }

        return new JsonResponse([
            'error' => $this->responsePhraseToCode($responsePhrase),
            'message' => $responsePhrase,
        ], $status);
    }

    /**
     * @param string $responsePhrase
     * @return string
     */
    protected function responsePhraseToCode($responsePhrase)
    {
        return strtoupper(str_replace(' ', '_', $responsePhrase));
    }
}
