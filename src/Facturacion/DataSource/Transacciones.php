<?php


namespace Facturacion\DataSource;


class Transacciones {

    protected $db;

    public function __construct(\mysqli $db ) {

        $this->db = $db;
    }

    public function startTransaction(){

        $this->db->query("start transaction");

        return 'trs';
    }

    public function grabarTransaction(){

        $this->db->query("commit");

        return 'trfin';
    }

    public function stopTransaction(){

        $this->db->query("rollback");

        return 'trno';
    }

}