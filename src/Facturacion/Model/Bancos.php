<?php


namespace Facturacion\Model;


use Facturacion\DAO\BancosDAO;

class Bancos {

    private $bancosDAO;

    public function __construct(BancosDAO $bancosDAO) {

        $this->bancosDAO = $bancosDAO;
    }

    public function getBancosEmp($idemp){
        $r = $this->bancosDAO->selectBancosEmp($idemp);

        return $r;
    }

    public function anadirBanco(array $d) {
        $r = $this->bancosDAO->insertBanco($d);

        return $r;

    }
    public function getAllBancosEmp($idemp){
        $r = $this->bancosDAO->selectAllBancosEmp($idemp);

        return $r;
    }

    public function modifcaBanco(array $d){
        $r = $this->bancosDAO->upadteBanco($d);

        return $r;
    }

    public function borraBanco(string $cuenta){
        $r = $this->bancosDAO->deleteBanco($cuenta);

        return $r;
    }
}