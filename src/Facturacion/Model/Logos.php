<?php


namespace Facturacion\Model;


use Facturacion\DAO\LogosDAO;

class Logos {

    private $logosDAO;

    public function __construct(LogosDAO $logosDAO) {

        $this->logosDAO = $logosDAO;
    }

    public function logoInicial(int $idemp){
        $r = $this->logosDAO->selectLogoInicial($idemp);

        return $r;
    }

    public function logosEmpresa(int $idemp){
        $r = $this->logosDAO->selectLogosEmp($idemp);

        return $r;
    }

    public function cambioLogoEmpresa(array $d){
        $r = $this->logosDAO->updateUltimoLogo($d);

        return $r;
    }
}