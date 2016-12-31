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

    public function logosEmpresa($idemp){
        $r = $this->logosDAO->selectLogosEmp($idemp);

        return $r;
    }

    public function cambioLogoEmpresa(array $d){
        $r = $this->logosDAO->updateUltimoLogo($d);

        return $r;
    }
}