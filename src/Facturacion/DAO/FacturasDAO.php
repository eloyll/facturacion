<?php


namespace Facturacion\DAO;


class FacturasDAO {

    private $db;
    private $facturasivaDAO;
    private $facturasvenciDAO;
    private $facturasitemsDAO;

    public function __construct(\mysqli $db, FacturasIvaDAO $facturasivaDAO, FacturasVenciDAO $facturasvenciDAO, FacturasItemsDAO $facturasitemsDAO) {

        $this->db = $db;
        $this->facturasivaDAO = $facturasivaDAO;
        $this->facturasvenciDAO = $facturasvenciDAO;
        $this->facturasitemsDAO = $facturasitemsDAO;
    }

    public function insertFactura(array $d){

        $ins = "insert into facturas (id_empresa, numero_fac, fecha_factura, empresa, cif, direccion, logo, registro_mercantil, cliente, cif_cliente, direc_cliente, obser, importe, tipo_iva, iva, retencion,pctge_retencion, recargo_equ, pctge_req, total,moneda,decimales,jash,exento_iva,texto_exento,tipo_fac) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $this->db->prepare($ins);
        $stmt->bind_param("isssssssssssssssssssssssss",$d['idempresa'],$d['numero_fact'],$d['fechafact'],$d['empresa'],$d['cif'],$d['direccion'],$d['logo'],$d['registro_mercantil'],$d['cliente'],$d['cif_cliente'],$d['direc_cliente'],$d['obser'],$d['importe'],$d['tipo_iva'],$d['iva'],$d['retencion'],$d['pctge_retencion'],$d['recargo_equ'],$d['pctge_req'],$d['total'],$d['moneda'],$d['decimales'],$d['jash'],$d['exento_iva'],$d['texto_exento'],$d['tipo-fac']);

        $r['fac'] = $stmt->execute();
        if(!$r['fac']){
            $r['ok'] = 'no';
        }else{
            $r['iva'] = $this->facturasivaDAO->insertIva($d['ivas'],$d['idempresa'],$d['numero_fact']);
            $r['ven'] = $this->facturasvenciDAO->insertCobros($d['venci'],$d['idempresa'],$d['numero_fact'],$d['decimales']);
            $r['itm'] = $this->facturasitemsDAO->isertItems($d['items'],$d['idempresa'],$d['numero_fact'],$d['decimales']);
        }

        if($r['iva'] === true && $r['ven'] === true && $r['itm'] === true){
            $r['ok'] = 'si';
        }else{
            $r['ok'] = 'no';
        }

        return $r;

    }

    public function selectFactura(array $d){

        $fac = "select id_empresa, numero_fac, date_format(fecha_factura,'%d/%m/%Y') as fecha_factura, empresa, cif, direccion, logo, registro_mercantil, cliente, cif_cliente, direc_cliente, obser, importe, tipo_iva, iva, retencion, pctge_retencion, recargo_equ, pctge_req, total, moneda, decimales, jash, exento_iva, texto_exento,tipo_fac from facturas where id_empresa='$d[idemp]' and numero_fac='$d[num]'";
        $rfac = $this->db->query($fac);
        if($rfac){
            $r['factura'] = $rfac->fetch_assoc();
            $r['ok'] = 'si';
        }else{
            $r['ok'] = 'no';
            $r['err'] = $this->db->error;
            return $r;
        }
        $fiva = "select pctge_iva, bi_iva, iva from facturas_iva where id_empresa='$d[idemp]' and numero_fac='$d[num]'";
        $rfiva = $this->db->query($fiva);
        if($rfiva){
            for($i=0;$i<$rfiva->num_rows;$i++){
                $r['iva'][$i] = $rfiva->fetch_assoc();
                $r['ok'] = 'si';
            }
        }else{
            $r['ok'] = 'no';
            $r['err'] = $this->db->error;
            return $r;
        }
        $fitem = "select cantidad, codigo, concepto, descuento, iva, precio, importe from facturas_items where id_empresa='$d[idemp]' and numero_fac='$d[num]'";
        $rfitem = $this->db->query($fitem);
        if($rfitem){
            for($i=0;$i<$rfitem->num_rows;$i++){
                $r['item'][$i] = $rfitem->fetch_assoc();
                $r['ok'] = 'si';
            }
        }else{
            $r['ok'] = 'no';
            $r['err'] = $this->db->error;
            return $r;
        }
        $fvcto = "select date_format(fecha,'%d/%m/%Y') as fecha, tipo, importe, numero_cuenta, swift from facturas_venci where id_empresa='$d[idemp]' and numero_fac='$d[num]' ";
        $rfvcto = $this->db->query($fvcto);
        if($rfvcto){
            for($i=0;$i<$rfvcto->num_rows;$i++){
                $r['vcto'][$i] = $rfvcto->fetch_assoc();
                $r['ok'] = 'si';
            }
        }else{
            $r['ok'] = 'no';
            $r['err'] = $this->db->error;
            return $r;
        }

        return array($r);
    }

}