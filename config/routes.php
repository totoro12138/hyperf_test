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

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::get('/favicon.ico', function () {
    return '';
});

Router::addRoute(['GET', 'POST', 'HEAD'], '/user/add', 'App\Controller\UserController@add');
Router::addRoute(['GET', 'POST', 'HEAD'], '/user/delete', 'App\Controller\UserController@delete');
Router::addRoute(['GET', 'POST', 'HEAD'], '/user/get', 'App\Controller\UserController@get');
Router::addRoute(['GET', 'POST', 'HEAD'], '/user/info', 'App\Controller\UserController@info');