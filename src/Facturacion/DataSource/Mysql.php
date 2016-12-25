<?php


namespace Facturacion\DataSource;


class Mysql {


    private static $db;
    private static $host;
    private static $user;
    private static $pass;
    private static $base;
    private static $port;
    private static $char;
    private static $errorMail = 'eloy@echip.es';


    public function __construct($host,$user,$pass,$base,$char,$port) {

        self::$host = $host;
        self::$user = $user;
        self::$pass = $pass;
        self::$base = $base;
        self::$char = $char;
        self::$port = $port;
    }

    public static function getInstance (){

        if(!self::$db instanceof \mysqli){

            self::$db = new \mysqli(
                self::$host,
                self::$user,
                self::$pass,
                self::$base,
                self::$port);

            if(self::$db->connect_errno){
                mail(self::$errorMail,'ERROR Mysql - Facturacion','Conexion con error en el servidor de Mysql');
                die('Error de conexiónes... Inténtalo más tarde ');

            }

            self::$db->set_charset(self::$char);



        }

        return self::$db;
    }



}
