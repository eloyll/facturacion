<?php


namespace Facturacion\DAO;


use Facturacion\DataSource\Transacciones;

class AlbaranesDAO {

    private $db;
    public function __construct(\mysqli $db) {

        $this->db = $db;
    }

    public function insertAlbaran(array $d){
        $ins = "insert into albaranes_items (id_empresa, cliente_cif, numero_alba, fecha_alba, cantidad, codigo, concepto, descuento, iva, precio, importe, nombre) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";

        $stmt = $this->db->prepare($ins);
        $stmt->bind_param('isssssssssss',$d[0],$d[1],$d[2],$d[3],$d[4],$d[5],$d[6],$d[7],$d[8],$d[9],$d[10],$d[11]);
        $r['alba'] = $stmt->execute();
        if($r['alba']){
            $r['ok'] = 'si';
            $r['numero_alba'] = $d[2];
            $r['cliente_cif'] = $d[1];
        }else{
            $r['ok'] = 'no';
        }

        return $r;
    }

    public function selectAlbaran(array $d){
        $sel = "select id_empresa, cliente_cif, numero_alba, date_format(fecha_alba,'%d/%m/%Y') as fecha_alba, cantidad, codigo, concepto, descuento, iva, precio, importe, nombre from albaranes_items where cliente_cif='$d[clicif]' and numero_alba='$d[num]' and facturado = 'no' ";
        $rsel = $this->db->query($sel);
        $r = [];
        for($i=0;$i<$rsel->num_rows;$i++){
            $r[] = $rsel->fetch_assoc();
        }

        return $r;
    }

    public function selectAlbaranCif(string $cif){
        $sel = "select id,id_empresa, cliente_cif, numero_alba,  date_format(fecha_alba,'%d/%m/%Y') as fecha_alba, cantidad, codigo, concepto, descuento, iva, precio, importe, nombre from albaranes_items where cliente_cif='$cif' and facturado='no' ORDER BY fecha_alba";
        $rsel = $this->db->query($sel);
        $r = [];
        for($i=0;$i<$rsel->num_rows;$i++){
            $r[] = $rsel->fetch_assoc();
        }
        $r['nl'] = $rsel->num_rows;
        return $r;
    }

    public function updateItemAlbaFact($id,$nf,$fac='si'){
        $upd = "update albaranes_items set facturado='$fac', numero_fac='$nf' where id='$id'";
        $rupd = $this->db->query($upd);

        return $rupd;

    }
}