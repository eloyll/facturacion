<?php


namespace Facturacion\Model;


use Facturacion\DAO\FacturasDAO;
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

    public function existeLogo(string $nombre){

        $r = $this->logosDAO->selectLogoNombre($nombre);
        if($r['nl'] > 0){
            $r['ok'] = 'no';
        }else{
            $r['ok'] = 'si';
        }

        return $r;
    }

    public function anadirLogo(array $d){
        $dir = '/static/img/logos/';
        $ruta = $_SERVER['DOCUMENT_ROOT'].'/static/img/logos/'.$d['nombre'];
        $data = explode("base64,",$d['base64']);
        $datos = base64_decode($data[1]);
        $r['file'] = file_put_contents($ruta,$datos);
        if(!$r['file']){
            $r['ok'] = 'no';
            $r['error'] = $ruta;
            return $r;
        }
        $d['ruta'] = $dir.$d['nombre'];
        $r = $this->logosDAO->insertLogo($d);

        return $r;
    }
    public function borraLogoId(int $id){
        $f = $this->logosDAO->selectFacturasLogo($id);
        if($f == 'si'){
            $r['ok'] = 'fac';
            $r['fac'] = 'si';
        }else{
            $r = $this->logosDAO->deleteLogoId($id);
        }

        return $r;
    }

    public function actualizaCampoFactura(string $logo){
        $r = $this->logosDAO->updateCampoFactura($logo);

        return $r;
    }
}