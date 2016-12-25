<?php

namespace Files\Sesiones;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ControlSesion {

    public $gestionsesion;

    public function __construct(GestionSesion $gestionSesion) {
        $this->gestionsesion = $gestionSesion;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next) {

        $ruta = $request->getUri()->getPath();
        switch($ruta){
            case '/':
                if($this->gestionsesion->existeKey('FAC-PREOK')) {
                    $this->gestionsesion->borraSesion();
                }
                break;
            case '/usuario':

                break;
            case '/hacerfactura':
                if(!$this->gestionsesion->existeKey('FAC-PREOK')) {
                    //return $response->withStatus(301)->withHeader('Location','errorsession');
                    return $response->withStatus(301)->withHeader('Content-Type', 'text/html')->write('<h2>Sesión Caducada, cierra la ventana y reinicia el navegador de la ventana principal.</h2><p><a href="javascript:window.close()">Cierra la ventana</a></p>');
                }
                break;
                break;
            case '/login':
                if(!$this->gestionsesion->existeKey('FAC-PREOK')) {
                    return $response->withStatus(301)->withHeader('Location','/');
                }
                break;

            default;
                if(!$this->gestionsesion->existeKey('FAC-PREOK')) {
                    if($request->getContentType() == 'application/json'){
                        $r['ok'] = 'session';
                        return $response->write(json_encode($r));
                    }else{
                        return $response->withStatus(301)->withHeader('Location','/');
                    }

                }
                break;
        }

        return $next($request, $response);

    }
}