<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;
use App\Middleware\IpAuthMiddleware;

Router::get('/favicon.ico', function () {
    return '';
});
Router::get('/', function () {
    return 'Payment successful ';
});
Router::get('/recharge/jump', 'App\Controller\IndexController@jump');
Router::addGroup('/api', function () {
        Router::post('/orders', 'App\Controller\IndexController@orders');
        Router::post('/payments/status', 'App\Controller\IndexController@paymentStatus');
        Router::post('/recharge/callback', 'App\Controller\IndexController@callback');
    }
);
