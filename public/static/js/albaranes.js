

$('#precio').keyup(function(e){
    if(e.keyCode == 13){
        g_keycode = 13;
        g_dato_input = $(this).val();
        g_id_input = $(this).prop('id');
        $('.decimals').trigger('blur');

    }

});

$('#btn-item').click(function(){
    var ltxt = $('#infoitem').html().length;
    if(ltxt > 2){
        modificaritem();
    }else {

        var iva = $('#iva').val();
        var patron = /^(\-)?[0-9]+([\.]?[0-9]{0,})$/;
        if(!patron.test(iva)){
            Modal.poner("Solo se adminten números y el signo 'menos'<br>Ejemplo: 150116.255 ó -2236.36","Error de formato");
            setTimeout(function(){
                $('#'+id).focus().select();
            },100);
            return false;
        }

        var conc = $('#concepto').val();
        if (conc.length < 1) {
            Modal.poner('Debes de poner el concepto', 'Error en el campo', 'concepto');
            return false;
        }
        var $pre = $('#precio');
        if ($pre.val().length < 1) {
            Modal.poner('Debes de poner el precio', 'Error en el campo', 'precio');
            return false;
        }
        if ($('#descuento').val().length < 1) {
            $('#descuento').val('0.00')
        }
        if ($('#cantidad').val().length < 1) {
            $('#cantidad').val('1.00')
        }

        var prec = parseFloat($pre.val());
        var cant = parseFloat($('#cantidad').val());
        var desc = parseFloat($('#descuento').val());
        var impo = prec * cant - (prec * cant * desc / 100);
        impo = (impo / 1).toFixed(g_decimales);
        g_items[g_conitem] = {
            "cantidad": $('#cantidad').val(),
            "codigo": $('#codigo').val(),
            "concepto": $('#concepto').val(),
            "descuento": $('#descuento').val(),
            "iva": $('#iva').val(),
            "precio": $pre.val(),
            "importe": impo
        };

      /*  if ($('#codigo').val().length < 1) {
            $('#codigo').val('&nbsp;')
        }
        g_totitem = parseFloat(g_totitem) + parseFloat(impo);
        g_totitem = (g_totitem / 1).toFixed(g_decimales);
        var item =
            '<div class="text-center" style="clear: both;float: left;width: 5%">' + g_items[g_conitem].cantidad + '</div>' +
            '<div class="text-center" style="float: left;width: 15%; margin-right: 10px">' + $('#codigo').val() + '</div>' +
            '<div class="text-left" style="float: left;width: 35%; margin-right: 5px">' + g_items[g_conitem].concepto + '</div>' +
            '<div class="text-center" style="float: left;width: 5%; margin-right: 5px">' + g_items[g_conitem].descuento + '</div>' +
            '<div class="text-center" style="float: left;width: 5%; margin-right: 5px">' + g_items[g_conitem].iva + '</div>' +
            '<div class="text-right" style="float: left;width: 10%; padding-right: 5px">' + g_items[g_conitem].precio + '</div>' +
            '<div class="text-right" style="float: left;width: 10%; padding-right: 5px">' + g_items[g_conitem].importe + '</div>' +
            '<div style="float: left;width: 3.5%;text-align: right"><i title="Modificar" style="color: #009;" class="fa fa-pencil" onclick="editaritem(\'' + g_conitem + '\')"></i>&nbsp;<i title="Borrar" style="color: #900;" class="fa fa-close" onclick="borraritem(\'' + g_conitem + '\')"></i></div>';

        $('#div-items').append(item);

        var total = calaculartotal();

        $('#cantidad').val('1.00').focus();
        $('#codigo').val('');
        $('#concepto').val('');
        $('#descuento').val(g_items[g_conitem].descuento);
        $('#iva').val(g_items[g_conitem].iva);
        $('#totimp').val((g_totitem/1).toFixed(g_decimales));
        $('#vimporte').val((total/1).toFixed(g_decimales));
        $('#totalfactura').empty().html((total/1).toFixed(g_decimales));*/

        g_conitem++;
        poneritems();
    }
});
$('#btn-limpiaritem').click(function(){
    $('#cantidad').val('1.00');
    $('#codio').val('');
    $('#concepto').val('');
    $('#descuento').val('0.00');
    $('#iva').val($('#usuiva').html());
    $('#precio').val('0.00');
    $('#infoitem').empty();
    g_moditem = 'no';

})
function editaritem(id){
    var art = parseInt(id)+1;
    g_moditem = id;

    $('#infoitem').empty().html('Modificando Artículo... '+art);
    $('#cantidad').val(g_items[id].cantidad);
    $('#codigo').val(g_items[id].codigo);
    $('#concepto').val(g_items[id].concepto);
    $('#descuento').val(g_items[id].descuento);
    $('#iva').val(g_items[id].iva);
    $('#precio').val(g_items[id].precio);
}

function modificaritem(){
    if(isNaN(g_moditem)){
        Modal.poner("No se pueden modificar los artículos",'Error','cantidad');
        return false;
    }
    var conc = $('#concepto').val();
    if(conc.length < 1){
        Modal.poner('Debes de poner el concepto','Error en el campo','concepto');
        return false;
    }
    var $pre = $('#precio');
    if($pre.val().length < 1){
        Modal.poner('Debes de poner el precio','Error en el campo','precio');
        return false;
    }
    if($('#descuento').val().length < 1){
        $('#descuento').val('0.00')
    }
    if($('#cantidad').val().length < 1){
        $('#cantidad').val('1.00')
    }


    var id = g_moditem;
    var prec = parseFloat($('#precio').val());
    var cant = parseFloat($('#cantidad').val());
    var desc = parseFloat($('#descuento').val());
    var impo = prec * cant - (prec * cant * desc / 100);
    impo = (impo/1).toFixed(g_decimales);
    g_items[id] =   {"cantidad":$('#cantidad').val(),
        "codigo":$('#codigo').val(),
        "concepto":$('#concepto').val(),
        "descuento":$('#descuento').val(),
        "iva":$('#iva').val(),
        "precio":$('#precio').val(),
        "importe":impo};

    poneritems();
}

function borraritem(id){

    if(!confirm("Seguro de borrar este artículo\n\n"+g_items[id].concepto.toUpperCase())){
        return false;
    }else{
        delete g_items[id];
        poneritems();
        if(Object.keys(g_items).length < 1){
            $('#div-vctos').empty();
            g_vctmo = {};
        }
    }
}

function poneritems(){
    var ni = Object.keys(g_items).length;
    g_totitem = 0;
    var item ='';

    //for(var i=0;i<ni;i++){
    for(var i in g_items){
        if(!( i in g_items)){
            continue;
        }
        var spac = '&nbsp;';
        if(g_items[i].codigo.length > 1){
           spac = g_items[i].codigo;
        }
        item +=
            '<div class="text-center" style="clear: both;float: left;width: 5%">'+g_items[i].cantidad+'</div>' +
            '<div class="text-center" style="float: left;width: 15%; margin-right: 10px">'+spac+'</div>'+
            '<div class="text-left" style="float: left;width: 35%; margin-right: 5px">'+g_items[i].concepto+'</div>'+
            '<div class="text-center" style="float: left;width: 5%; margin-right: 5px">'+g_items[i].descuento+'</div>'+
            '<div class="text-center" style="float: left;width: 5%; margin-right: 5px">'+g_items[i].iva+'</div>'+
            '<div class="text-right" style="float: left;width: 10%; padding-right: 5px">'+g_items[i].precio+'</div>'+
            '<div class="text-right" style="float: left;width: 10%; padding-right: 5px">'+g_items[i].importe+'</div>'+
            '<div style="float: left;width: 3.5%;text-align: right"><i title="Modificar" style="color: #009;" class="fa fa-pencil" onclick="editaritem(\''+i+'\')"></i>&nbsp;<i title="Borrar" style="color: #900;" class="fa fa-close" onclick="borraritem(\''+i+'\')"></i></div>';
        g_totitem = parseFloat(g_totitem) + parseFloat(g_items[i].importe);
    }
    g_moditem = 'no';
    $('#div-items').empty().html(item);

    var total = calaculartotal();

    $('#cantidad').val('1.00').focus();
    $('#codigo').val('');
    $('#concepto').val('');
    $('#descuento').val('0.00');
    $('#precio').val((0/1).toFixed(g_decimales));
    $('#totimp').val((g_totitem/1).toFixed(g_decimales));
    $('#infoitem').empty();
    $('#div-vctos').empty();

}

$('#precio').focus(function() {
   $(this).select();
});

function calaculartotal(){
    /*g_iva = {};
    g_ret = 0;
    g_req = 0;*/
    g_totitem = 0;
    //var giva = 0;

    for (var i in g_items){
        var imp = 0;
        imp = parseFloat(g_items[i].cantidad) * parseFloat(g_items[i].precio);
        imp = imp - (imp * parseFloat(g_items[i].descuento / 100));
        /*if(!(g_items[i].iva in g_iva)){
            g_iva[g_items[i].iva] = 0;
            g_iva['bi_'+String(g_items[i].iva)] = 0;
            //g_bi_iva[g_items[i].iva] = 0;
        }
        var iva = imp * parseFloat(g_items[i].iva) / 100;
        //g_bi_iva[g_items[i].iva] = g_bi_iva[g_items[i].iva] + imp;
        g_iva['bi_'+String(g_items[i].iva)] = g_iva['bi_'+String(g_items[i].iva)] + imp;
        iva = parseFloat((iva/1).toFixed(2));
        g_iva[g_items[i].iva] = g_iva[g_items[i].iva] + parseFloat(iva.toFixed(2));*/
        g_totitem = g_totitem + imp;
        //giva = giva + iva;
    }
    /*var ret = parseFloat($('#usuret').html());
    g_ret = parseFloat((g_totitem * ret / 100).toFixed(2));
    var req = parseFloat($('#usureq').html());
    g_req = parseFloat((g_totitem * req / 100).toFixed(2));
    g_total = g_totitem - g_ret + giva + g_req;*/

    //return g_total;
    return g_totitem;
}

$('#btn-vaciaitems').click(function(){
    if(!confirm("Vas a borrar los elementos del albarán")){
        return false;
    }
    g_items = {};
    $('#div-items').empty();

});

$('#btn-verfactura').click(function() {
    var nitems = Object.keys(g_items).length;

    if(nitems < 1){
        Modal.poner('Debes de poner por lo menos un producto','Factura vacia','cantidad');
        return false;
    }

    var datos = {"items": g_items,
                "usufechafac":fechamysql($('#usufechafac').val()),
                "albacif":$('#albacif').val()};

    var lnom = $('#factnombre').html().trim();

    if(lnom < 2){
        $('#albacif').trigger('focusout');
    }

    $.ajax({
        type:'post',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:'/validardatosalbaran',
        data:JSON.stringify(datos),
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'si':
                    albaranes();
                    break;
                case 'no':
                    switch (data['id']) {
                        case 'usufechafac':
                            $('#' + data['id']).errorForm();
                            Modal.poner('Fecha de la factura con errores', 'Error en los datos', 'usufechafac');
                            break;
                        case 'albacif':
                            $('#' + data['id']).errorForm();
                            Modal.poner('El cliente no existe, revisa el CIF', 'Error en los datos', 'factcif');
                            break;
                        case 'items':
                            $('#cantidad').errorForm();
                            Modal.poner('Debes de facturar un producto', 'Items de la factura', 'cantidad');
                            break;
                        case 'cantidad':
                        case 'descuento':
                        case 'iva':
                        case 'precio':
                            $('#' + data['id']).errorForm();
                            Modal.poner('Solo números, un  signo menos y un punto<br>Revisa el dato ('+data['id']+') de los productos', 'Formato numérico', data['id']);
                            break;
                    }
                    break;
                case 'sesion':
                    window.self.location.assign('/');
                    break;


            }
        }
    });
});


function albaranes() {
    var idemp = $('#sel-empresa').val();
    var datosempresa = {};
    datosempresa = {"fecha_alba":fechamysql($('#usufechafac').val()),
        "numero_alba":$('#usunumfac').val(),
        "importe":g_totitem,
        "id_empresa":idemp
    };
    var cliente = {};

    cliente = {"cif":$('#albacif').val(),
        "nombre":$('#factnombre').html(),
        "direccion":$('#factdirec').html(),
        "recoje":$('#quienrecoje').val()};

    var datos = {};

    datos['albaitems'] = JSON.stringify(g_items);
    datos['albadatos'] = JSON.stringify(datosempresa);
    datos['albacliente'] = JSON.stringify(cliente);

    $.ajax({
        type:'post',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:'/grabaralbaran',
        data:JSON.stringify(datos),
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'si':
                    var url = "/albaran/"+data['numero_alba']+"/"+data['cliente_cif'];
                    var mipop = window.open(url,data['numero_alba'],"width=800,height=600,resizable=yes,top=30,left=100,menubar=yes,location=no,scrollbars=yes");
                    mipop.focus();
                    self.location.reload(true);
                    break;
                case 'no':
                    Modal.poner('Error en los datos','ALBARAN','importe');
                    break;
                case 'sesion':
                    window.self.location.assign('/');
                    break;


            }
        }
    });
}


//Visualizar acerca de...

$('#a-acercade').click(function(){

    $('#acercade').fadeIn('slow',function(){
        $('#acercade-in').focus();
    });

});
$('#acercade-in').blur(function(){

    $('#acercade').fadeOut('slow')
});
//-----------------------------------

$('#albacif').focusout(function(){
    var datos = {};
    datos['cif'] = $(this).val();
    $.ajax({
        type:'GET',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:'/cifcliente/'+datos['cif'],
        data:'',
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'si':
                    if(data['nl'] < 1){
                        Modal.poner("Ese cliente no existe",'Error en el cliente','factcif');
                        break;
                    }
                    $('#factnombre').empty().html(data['nombre']);
                    var dire = data['direccion']+"\n"+data['cp']+' - '+priMay(data['poblacion'])+"\n"+priMay(data['provincia'])+' ('+priMay(data['pais'])+')';
                    dire = dire.replace(/\n/g,"<br>");
                    $('#factdirec').empty().html(dire);
                    $('#factdesc').empty().html(data['descuento']);
                    $('#descuento').val(data['descuento']);
                    //$('#descuento').val(data['descuento'])
                    break;
                case 'no':
                    Modal.poner('No está disponible el servicio','Error en la Red','albacif');
                    break;
                case 'sesion':
                    window.self.location.assign('/');
                    break;


            }
        }
    });
});

$('#sel-empresa').change(function(){
    var datos = {};
    datos['id'] = $(this).val();
    var ruta = "/empresa/"+datos['id'];

    $.ajax({
        type:'GET',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:ruta,
        data:'',
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'si':
                    g_items = {};
                    $('#div-items').empty();
                    $('#usunombre').empty().html(priMay(data['nombre']));
                    $('#usucif').empty().html(data['cif'].toUpperCase());
                    var fec = new Date();
                    var na = String(Math.round(fec.getTime()/1000));
                    var numerofac = 'ALB-'+na.substring(na.length-7);
                    $('#usunumfac').val(numerofac);
                    $('#factcif').val('');
                    $('#factnombre').html('');
                    $('#factdirec').html('');
                    $('#usufechafac').val(data['fecha_factura']);
                    $('#logoempresa').prop('src',data['logo']);
                    $('#item-tipoiva').empty().html(data['tipo_iva']);
                    $('#iva').prop({'placeholder':data['tipo_iva'],'title':data['tipo_iva']}).val(data['iva']);
                    $('#simb-moneda,#simb-moneda2').empty().html(data['cf_mo_simbolo']);
                    g_decimales = data['cf_decimales'] ;
                    $('#precio').val((0/1).toFixed(g_decimales));
                    $('#concepto').val('');
                    $('#totimp').val((0/1).toFixed(g_decimales));
                   // $('#vimporte').val((0/1).toFixed(g_decimales));alert('ppp')
                    break;
                case 'no':
                    Modal.poner('No está disponible el servicio','Error en la Red','usu-nombre');
                    break;
                case 'sesion':
                    window.self.location.assign('/');
                    break;


            }
        }
    });

});



$(document).ready(function(){

    g_ancho = $(window).width();
    var disp = navigator.userAgent.toLowerCase();
   if(disp.search(/iphone|ipod|ipad|android|sailfish|mobile/) > -1){
        g_navega = 'movil';
        //$('#llamada').css('text-align','left');

    }else{
        g_navega = 'pc';
        //$('#llamada').css('text-align','right');
    }

    $('#precio,#totimp,#vimporte').val((0/1).toFixed(g_decimales));
    $('#totalfactura').empty().html((0/1).toFixed(g_decimales))
    $('#vfecha').val(g_hoy);
    $('#fact-obser').val('');

});