<?php


namespace Facturacion\DataSource;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Conect {


    private static $mysql;

    public function __construct(Mysql $mysql) {

        self::$mysql = $mysql;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next) {

        return $next($request, $response);
    }
}