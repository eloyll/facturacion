<?php


namespace Facturacion\Model;


use Facturacion\DAO\AuxFormasPagoDAO;

class AuxFormasPago {

    private $auxformaspagoDAO;

    function __construct(AuxFormasPagoDAO $auxformaspagoDAO) {

        $this->auxformaspagoDAO = $auxformaspagoDAO;
    }

    public function getFormasPago(){

        $r = $this->auxformaspagoDAO->selectFormasPago();

        return $r;
    }
}