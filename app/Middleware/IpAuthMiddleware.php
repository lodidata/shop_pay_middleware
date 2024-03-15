<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Support\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\DbConnection\Db;

class IpAuthMiddleware implements MiddlewareInterface
{
    /**
     * @Inject
     * @var Response
     */
    protected $response;


    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $client_ip = get_real_ip();
        if (!empty($client_ip)) {
            return $this->response->fail();
        } else {
            return $this->response->fail('Get IP error');
        }
    }
}
