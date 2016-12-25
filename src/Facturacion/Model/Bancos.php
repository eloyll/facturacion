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
}