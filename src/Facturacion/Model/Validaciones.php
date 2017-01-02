<?php


namespace Facturacion\Model;

use Files\Validaciones\ValidacionesECHIP;

class Validaciones {

    private $validar;



    public function __construct(ValidacionesECHIP $validacionesECHIP) {

        $this->validar = $validacionesECHIP;


    }

    public function validarDatos(array $d){
        $v = ['value'=>$d['usufechafac'],'tipo'=>'fecha','id'=>'usufechafac'];
        $r = $this->validaDatosFactura($v);
        if($r['ok'] == 'no'){
            return $r;
        }

        $r = [];
        $r['ok'] = 'si';
        if(count($d['items']) < 1){
            $r['ok'] = 'no';
            $r['id'] = 'items';
            return $r;
        }

        $r = $this->validaItems($d['items']);

        if($r['ok'] == 'no'){
            return $r;
        }
        if(count($d['venci']) < 1){
            $r['ok'] = 'no';
            $r['id'] = 'venci';
            return $r;
        }
        foreach($d['venci'] as $k => $v) {

            $dv = ['value'=>$d['venci'][$k]['vfecha'],'tipo'=>'fecha','id'=>'vfecha'];

            $r = $this->validaDatosFactura($dv);
            if($r['ok'] == 'no'){
                break;
            }
        }



        return $r;
    }

    public function validaItems(array $d){
        foreach($d as $i => $v){

            $di = ['value'=>$d[$i]['cantidad'],'tipo'=>'float','id'=>'cantidad'];
            $r = $this->validaDatosFactura($di);
            if($r['ok'] == 'no'){
                break;
            }
            $di = ['value'=>$d[$i]['descuento'],'tipo'=>'float','id'=>'descuento'];
            $r = $this->validaDatosFactura($di);
            if($r['ok'] == 'no'){
                break;
            }
            $di = ['value'=>$d[$i]['iva'],'tipo'=>'float','id'=>'iva'];
            $r = $this->validaDatosFactura($di);
            if($r['ok'] == 'no'){
                break;
            }
            $di = ['value'=>$d[$i]['precio'],'tipo'=>'float','id'=>'precio'];
            $r = $this->validaDatosFactura($di);
            if($r['ok'] == 'no'){
                break;
            }
        }
        if($r['ok'] == 'no'){
            return $r;
        }else{
            $r['ok'] = 'si';
            return $r;
        }

    }

    public function validaDatosFactura(array $d){

        $r = $this->validar->control_inputs($d);

        return $r;
    }

    public function validarDatosAlbaran(array $d){

        $v = ['value'=>$d['usufechafac'],'tipo'=>'fecha','id'=>'usufechafac'];
        $r = $this->validaDatosFactura($v);
        if($r['ok'] == 'no'){
            return $r;
        }

        $r = [];
        $r['ok'] = 'si';
        if(count($d['items']) < 1){
            $r['ok'] = 'no';
            $r['id'] = 'items';
            return $r;
        }
        $r = $this->validaItems($d['items']);

        return $r;
    }

    public function validarCliente(array $d){
        $cifcli = '';
        foreach ($d as $k => $t){
            foreach ($t as $c => $u) {
                switch ($c){
                    case 'value':
                        $v['value'] = $u;
                        break;
                    case 'tipo':
                        $v['tipo'] = $u;
                        break;
                    case 'name':
                        $v['id'] = $u;
                        break;
                }
                if($t['name'] == 'cif'){
                    $cifcli = $t['value'];
                }
                $r[$t['name']] = $t['value']; //si es correcto devuelve el array para el insert
            }

            $f = $this->validaDatosFactura($v);
            if($f['ok'] == 'no'){
                //$r['ok'] = 'no';
                return $f;
            }
        }

        $r['ok'] = 'si';
        return $r;
    }

    public function validarEmpresa(array $d){
        foreach ($d as $a){
            $s = $this->validaDatosFactura($a);
            if($s['ok'] == 'no'){
                return $s;
            }
            $r[$a['id']] = $a['value']; //devuelve el array para insertarlo en la BBDD
        }

        $r['ok'] = 'si';
        return $r;

    }
}