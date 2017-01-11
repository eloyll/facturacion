<?php


namespace Facturacion\Model;


use Facturacion\DAO\LogosDAO;

class Logos {

    private $logosDAO;

    public function __construct(LogosDAO $logosDAO) {

        $this->logosDAO = $logosDAO;
    }

    public function logoInicial($idemp){
        $r = $this->logosDAO->selectLogoInicial($idemp);

        return $r;
    }
    public function logoId($id){
        $r = $this->logosDAO->selectLogoId($id);

        return $r;
    }

    public function logosEmpresa($idemp){
        $r = $this->logosDAO->selectLogosEmp($idemp);

        return $r;
    }

    public function cambioLogoEmpresa(array $d){
        $r = $this->logosDAO->updateUltimoLogo($d);

        return $r;
    }

    public function anadirLogo(array $d){
        $dir = '/static/img/logos/';
        $ruta = $_SERVER['DOCUMENT_ROOT'].'/static/img/logos/'.$d['nombre'];
        $data = explode("base64,",$d['base64']);
        $datos = base64_decode($data[1]);
        //$r['file'] = fopen($ruta,'x');
        $r['file'] = file_put_contents($ruta,$datos);
        if(!$r['file']){
            $r['ok'] = 'no';
            $r['error'] = $ruta;
            return $r;
        }
       /* fwrite($ruta,$d['base64']);
        fclose($ruta);*/
        $d['ruta'] = $dir.$d['nombre'];
        $r = $this->logosDAO->insertLogo($d);

        return $r;
    }
    public function borraLogoId(int $id){
        $r = $this->logosDAO->deleteLogoId($id);

        return $r;
    }
}