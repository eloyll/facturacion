<?php
// DIC configuration


use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Facturacion\Controller\MainController;
use Facturacion\DataSource\Mysql;
use Files\Sesiones\ControlSesion;



$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

//Twig
$container['view'] = function($c){
    $settings = $c->get('settings')['renderer'];
    $view = new Twig($settings['template_path']);
    $view->addExtension(
        new TwigExtension(
            $c['router'],
            $c['request']->getUri()
        )
    );
    $view->addExtension(new Twig_Extension_StringLoader());

    return $view;

};

$container['conect'] = function($c){
    $settings = $c->get('settings')['mysql'];
    $mysql = new Facturacion\DataSource\Mysql($settings['host'],$settings['user'],$settings['pass'],$settings['base'],$settings['char'],$settings['port']);
    $conect = new Facturacion\DataSource\Conect($mysql);

    return $conect;

};

$container['sesiones'] = function($c){
    $gestionsesion = $c->get('gestionsesion');
    $sesiones = new ControlSesion($gestionsesion);

    return $sesiones;
};

$container['transacciones'] = function($c){
    $transacciones = new \Facturacion\DataSource\Transacciones(Mysql::getInstance());

    return $transacciones;
};

$container['gestionsesion'] = function($c){
    $gestionsesion = new Files\Sesiones\GestionSesion();

    return $gestionsesion;
};

$container['usuariosDAO'] = function($c){
  $usuariosDAO = new Facturacion\DAO\UsuariosDAO(Mysql::getInstance());

    return $usuariosDAO;
};

$container['empresasDAO'] = function($c){
    $empresasDAO = new Facturacion\DAO\EmpresasDAO(Mysql::getInstance());

    return $empresasDAO;
};
$container['clientesDAO'] = function($c){
    $clientesDAO = new Facturacion\DAO\ClientesDAO(Mysql::getInstance());

    return $clientesDAO;
};

$container['configuracionesDAO'] = function($c){
    $configuracionesDAO = new Facturacion\DAO\ConfiguracionesDAO(Mysql::getInstance());

    return $configuracionesDAO;
};

$container['datosempresasDAO'] = function($c){
    $datosempresasDAO = new Facturacion\DAO\DatosEmpresasDAO(Mysql::getInstance());

    return $datosempresasDAO;
};

$container['facturasivaDAO'] = function($c){
    $facturasivaDAO = new Facturacion\DAO\FacturasIvaDAO(Mysql::getInstance());

    return $facturasivaDAO;
};

$container['facturasvenciDAO'] = function($c){
    $facturasvenciDAO = new \Facturacion\DAO\FacturasVenciDAO(Mysql::getInstance());

    return $facturasvenciDAO;
};

$container['facturasitemsDAO'] = function($c){
    $albaranesDAO = $c->get('albaranesDAO');
    $facturasitemsDAO = new \Facturacion\DAO\FacturasItemsDAO(Mysql::getInstance(),$albaranesDAO);

    return $facturasitemsDAO;
};

$container['facturasDAO'] = function($c){
    $facturasivaDAO = $c->get('facturasivaDAO');
    $facturasvenciDAO = $c->get('facturasvenciDAO');
    $facturasitemsDAO = $c->get('facturasitemsDAO');
    $facturasDAO = new Facturacion\DAO\FacturasDAO(Mysql::getInstance(),$facturasivaDAO,$facturasvenciDAO,$facturasitemsDAO);

    return $facturasDAO;
};

$container['albaranesDAO'] = function($c){

    $albaranesDAO = new \Facturacion\DAO\AlbaranesDAO(Mysql::getInstance());

    return $albaranesDAO;
};

$container['bancosDAO'] = function($c){
    $bancosDAO = new \Facturacion\DAO\BancosDAO(Mysql::getInstance());

    return $bancosDAO;
};

$container['logosDAO'] = function($c){
    $logosDAO = new \Facturacion\DAO\LogosDAO(Mysql::getInstance());

    return $logosDAO;
};

$container['auxprovinciasDAO'] = function($c){
    $auxprovinciasDAO = new Facturacion\DAO\AuxProvinciasDAO(Mysql::getInstance());

    return $auxprovinciasDAO;
};

$container['auxformaspagoDAO'] = function($c){
    $formaspagoDAO = new Facturacion\DAO\AuxFormasPagoDAO(Mysql::getInstance());

    return $formaspagoDAO;
};

$container['auxformaspago'] = function($c){
    $auxformaspagoDAO = $c->get('auxformaspagoDAO');
    $auxformaspago = new \Facturacion\Model\AuxFormasPago($auxformaspagoDAO);

    return $auxformaspago;
};

$container['validacionesECHIP'] = function($c){
    $validacionesECHIP = new Files\Validaciones\ValidacionesECHIP();

    return $validacionesECHIP;
};

$container['validaciones'] = function($c){
    $validacionesEchip = $c->get('validacionesECHIP');
    $clientes = $c->get('clientes');
    $validaciones = new Facturacion\Model\Validaciones($validacionesEchip,$clientes);

    return $validaciones;
};

$container['usuarios'] = function($c){
    $usuariosDAO = $c->get('usuariosDAO');
    $gestionsesion = $c->get('gestionsesion');
    $usuarios = new Facturacion\Model\Usuarios($usuariosDAO,$gestionsesion);

    return $usuarios;
};

$container['empresas'] = function($c){
    $gestionsesion = $c->get('gestionsesion');
    $empresasDAO = $c->get('empresasDAO');
    $datosempresasDAO = $c->get('datosempresasDAO');
    $configuracionesDAO = $c->get('configuracionesDAO');
    $bancos = $c->get('bancos');
    $logos = $c->get('logos');
    $empresas = new Facturacion\Model\Empresas($empresasDAO,$datosempresasDAO,$gestionsesion,$configuracionesDAO,$bancos,$logos);

    return $empresas;
};

$container['clientes'] = function($c){
    $clientesDAO = $c->get('clientesDAO');
    $clientes = new Facturacion\Model\Clientes($clientesDAO);

    return $clientes;
};

$container['facturas'] = function($c){
    $facturasDAO = $c->get('facturasDAO');
    $datosempresasDAO = $c->get('datosempresasDAO');
    $gestionsesion = $c->get('gestionsesion');
    $transacciones = $c->get('transacciones');
    $facturas = new Facturacion\Model\Facturas($facturasDAO,$datosempresasDAO,$gestionsesion,$transacciones);

    return $facturas;
};

$container['bancos'] = function($c){
    $bancosDAO = $c->get('bancosDAO');
    $bancos = new \Facturacion\Model\Bancos($bancosDAO);

    return $bancos;
};

$container['logos'] = function($c){
    $logosDAO = $c->get('logosDAO');
    $logos = new \Facturacion\Model\Logos($logosDAO);

    return $logos;
};

$container['main'] = function($c){
    $gestionsesion = $c->get('gestionsesion');
    $clientes = $c->get('clientes');
    $empresas = $c->get('empresas');
    $bancos = $c->get('bancos');
    $logos = $c->get('logos');
    $auxprovinciasDAO = $c->get('auxprovinciasDAO');
    $auxformaspago = $c->get('auxformaspago');
    $validaciones = $c->get('validaciones');

    $main = new \Facturacion\Model\Main($gestionsesion,$empresas,$clientes,$bancos,$logos,$auxprovinciasDAO,$auxformaspago,$validaciones);

    return $main;
};

$container['albaranes'] = function($c){
    $albaranesDAO = $c->get('albaranesDAO');
    $transacciones = $c->get('transacciones');
    $clientesDAO = $c->get('clientesDAO');
    $albaranes = new \Facturacion\Model\Albaranes($albaranesDAO,$transacciones,$clientesDAO);

    return $albaranes;
};

//MainControler
$container['MainController'] = function($c){
    $View = $c->get('view');
    $usuarios = $c->get('usuarios');
    $empresas = $c->get('empresas');
    $clientes = $c->get('clientes');
    $validaciones = $c->get('validaciones');
    $facturas = $c->get('facturas');
    $main = $c->get('main');
    $albaranes = $c->get('albaranes');
    //$renderer = $c->get('renderer');

    $MainController = new MainController($View,$usuarios,$empresas,$clientes,$validaciones,$facturas,$main,$albaranes);

    return $MainController;
};
