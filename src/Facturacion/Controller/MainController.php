<?php

namespace Facturacion\Controller;

use Facturacion\Model\Albaranes;
use Facturacion\Model\Clientes;
use Facturacion\Model\Empresas;
use Facturacion\Model\Facturas;
use Facturacion\Model\Main;
use Facturacion\Model\Validaciones;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Facturacion\Model\Usuarios;


class MainController {

    private $view;
    private $usuarios;
    private $empresas;
    private $clientes;
    private $validaciones;
    private $facturas;
    private $main;
    private $albaranes;

    public function __construct($View, Usuarios $usuarios,Empresas $empresas, Clientes $clientes, Validaciones $validaciones, Facturas $facturas, Main $main, Albaranes $albaranes) {

        $this->view = $View;
        $this->usuarios = $usuarios;
        $this->empresas = $empresas;
        $this->clientes = $clientes;
        $this->validaciones = $validaciones;
        $this->facturas = $facturas;
        $this->main = $main;
        $this->albaranes = $albaranes;

    }


    public function index(RequestInterface $request, ResponseInterface $response, array $args){
        /*if(isset($_SESSION['usuario'])) {
            session_destroy();
            $cuki = session_get_cookie_params();
            setcookie(session_name(), 0, 1, $cuki["path"]);
            unset($_SESSION['usuario'],$_SESSION['nivel']);
        }*/

        $ip = $_SERVER['REMOTE_ADDR'];
        /*switch($ip){
            case  '84.76.184.8':
            //case  '217.125.137.163':
            case  'localhost':
            case  '127.0.0.1':
            case  '::1':
                return $this->view->render($response, "index.html.twig",["datos"=>$ip]);
                break;
            default:
                return $this->view->render($response, "index_no.html.twig",["datos"=>$ip]);
                break;
        }*/
        return $this->view->render($response, "index.html.twig",["datos"=>$ip]);

    }

    public function usuario(RequestInterface $request, ResponseInterface $response, array $args){

        $params = $request->getParsedBody();
        $r = $this->usuarios->usuario($params);
        $r['kiki'] = $request->getContentType();
        return json_encode($r);
    }

    public function login(RequestInterface $request, ResponseInterface $response, array $args){


        $params = $request->getParsedBody();
        $r = $this->usuarios->login($params);


        return json_encode($r);
    }

    public function main(RequestInterface $request, ResponseInterface $response, array $args){

        $r = $this->main->inicio();

        if($request->getContentType() == 'application/json'){
            return json_encode($r['empresa']);
        }else{

            return $this->view->render($response, "main.html.twig",["usuario" => $r['usuario'],
                "empre" => $r['empre'], "inicial" => $r['empresa'], "clienteslist" => $r['clientes'],"bancos" => $r['bancos'],"logos" => $r['logos'],"formaspago"=>$r['formaspago']]);
        }
    }

    public function cifcliente(RequestInterface $request, ResponseInterface $response, array $args){

        $p = $args['cif'];
        $r = $this->clientes->clienteCif($p);

        return json_encode($r);
    }

    public function getempresaid(RequestInterface $request, ResponseInterface $response, array $args){

        $id = $args['id'];
        $r = $this->empresas->cambioEmpresa($id);

        return json_encode($r);
    }

    public function validardatosfactura(RequestInterface $request, ResponseInterface $response, array $args){
        $params = $request->getParsedBody();
        $r = $this->validaciones->validarDatos($params);
        return json_encode($r);
    }

    public function hacerfactura(RequestInterface $request, ResponseInterface $response, array $args){

        $params = $request->getParsedBody();
        $r = $this->facturas->hacerFactura($params);

        return json_encode($r);

        /*if($r['ok'] == 'no'){
            //$body = $response->getBody()->write("Servicio interrunpido, inténtelo más tarde");
            return $this->view->render($response, "error_slim.html.twig");
        }else{
            return $this->view->render($response, "factura.html.twig",["dni"=>$data['0']['precio']]);
        }*/
    }

    public function getfacturanum(RequestInterface $request, ResponseInterface $response, array $args){

        $num = $request->getQueryParams();
        $r = $this->facturas->getFactura($args);
        if($request->getContentType() == 'application/json'){
            return json_encode($r);
        }else {
            if($r['ok'] == 'no'){
                $error = "No se encuentra esa factura (".$args['num']."), o el servidor no esta en servicio. Inténtalo más tarde.";
                return $this->view->render($response, "error_slim.html.twig",["error"=>$error]);
            }else{
                return $this->view->render($response, "factura.html.twig",["factura"=>$r[0]['factura'],"items"=>$r[0]['item'],"iva"=>$r[0]['iva'],"vcto"=>$r[0]['vcto']]);
            }
        }
    }

    public function haceralbaran(RequestInterface $request, ResponseInterface $response, array $args){

        $r = $this->main->hacerAlbaran();

        if($request->getContentType() == 'application/json'){
            return json_encode($r['empresa']);
        }else{

            return $this->view->render($response, "albaranes.html.twig",["usuario" => $r['usuario'],
                "empre" => $r['empre'], "inicial" => $r['empresa'], "clienteslist" => $r['clientes']]);
        }
    }

    public function validardatosalbaran(RequestInterface $request, ResponseInterface $response, array $args){
        $params = $request->getParsedBody();
        $r = $this->validaciones->validarDatosAlbaran($params);
        return json_encode($r);
    }

    public function grabaralbaran(RequestInterface $request, ResponseInterface $response, array $args){
        $params = $request->getParsedBody();
        $r = $this->albaranes->grabarAlbaran($params);
        return json_encode($r);
    }

    public function getalbanum(RequestInterface $request, ResponseInterface $response, array $args){
        $r = $this->albaranes->getAlbaran($args);
        if($request->getContentType() == 'application/json'){
            return json_encode($r);
        }else {
            if($r['ok'] == 'no'){
                $error = "No se encuentra ese Albarán (".$args['num']."), o el servidor no esta en servicio. Inténtalo más tarde.";
                return $this->view->render($response, "error_slim.html.twig",["error"=>$error]);
            }else{
                return $this->view->render($response, "albaran.html.twig",["albaran"=>$r['albaran'],"items"=>$r['items'],"empresa"=>$r['empresa'],"cliente"=>$r['cliente']]);
            }
        }
    }

    public function getalbaranescif(RequestInterface $request, ResponseInterface $response, array $args){
        $r = $this->albaranes->getAlbaranescif($args);
        if($request->getContentType() == 'application/json'){
            return json_encode($r);
        }else {
            if($r['ok'] == 'no'){
                $error = "Hay un error en el servidor o no esta en servicio. Inténtalo más tarde.";
                return $this->view->render($response, "error_slim.html.twig",["error"=>$error]);
            }else{
                print_r($r);
                return $this->view->render($response, "albaranes_cif.html.twig",["items"=>$r['albaranes'],"empresa"=>$r['empresa'],"cliente"=>$r['cliente']]);
            }
        }
    }

    public function cambiologo(RequestInterface $request, ResponseInterface $response, array $args){
        $params = $request->getParsedBody();
        $r = $this->empresas->cambioLogo($params);

        return json_encode($r);
    }

    public function clientes(RequestInterface $request, ResponseInterface $response, array $args){

        $r = $this->main->clientes($args);

        return $this->view->render($response, "clientes.html.twig",["usuario"=>$r['usuario'],"provincias"=>$r['provincias'],"formaspago"=>$r['formaspago'],"id"=>$r['id']]);
    }





}