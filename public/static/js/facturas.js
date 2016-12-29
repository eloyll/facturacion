var g_trgexe = '';
var g_optdialog = {
    autoOpen: false,
    height: 600,
    //minHeight: 400,
    width: 850,
    zindex: 10000,
    draggable: true,
    resizable: true,
    position: {my: "top-250", at: "right"},
    closeText: "Cerrar/Close",
    hide: {effect: "fadeOut", duration: 500},
    show: {effect: "fadeIn", duration: 500},
    close: function (event, ui) {
        //$(this).dialog("destroy");
    }
    /* open: function (event, ui) {

     }*/


};

function decimal2(da,id){

    var valor = '';
    var patron = /^(\-)?[0-9]+([\.])?[0-9]{0,}$/;
    if(!patron.test(da)){
        Modal.poner("Solo se adminten números y el signo 'menos'<br>Ejemplo: -3.36 ó 236","Error en el número");
        setTimeout(function(){
            $('#'+id).focus().select();
        },100);
        return false;
    }else{
        valor = (da/1).toFixed(2);
        $('#'+id).val(valor);
        return true;
    }
}

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
            "importe": impo,
            "cliente_cif":"",
            "numero_alba":"",
            "idalba":"--"
        };

        if ($('#codigo').val().length < 1) {
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
        $('#totalfactura').empty().html((total/1).toFixed(g_decimales));

        g_conitem++;
    }
});
$('#btn-limpiaritem').click(function(){
    $('#cantidad').val('1.00');
    $('#codio').val('');
    $('#concepto').val('');
    $('#descuento').val('0.00');
    $('#iva').val($('#usuiva').val());
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
        console.log(g_items);
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
    var siniva = $('input[name=exentoiva]:checked').val();
    $('#iva').val($('#usuiva').val());
    if(siniva == 'si'){
        $('#iva').val('0.00');
    }
    $('#precio').val('0.00');
    $('#totimp').val((g_totitem/1).toFixed(g_decimales));
    $('#vimporte').val((total/1).toFixed(g_decimales));
    $('#totalfactura').empty().html((total/1).toFixed(g_decimales));
    $('#infoitem').empty();
    $('#div-vctos').empty();
    g_vctmo = {}
}

$('#precio').focus(function() {
    $(this).select();
});

$('#btn-v-anadir').click(function(){
    var vfecha = $('#vfecha').val();
    var vtipo = $('#vtipo').val();
    var vimpo = $('#vimporte').val();
    var vbanco = $('#vbanco').val().split('#');
    if(parseFloat(vimpo) == 0 || Object.keys(g_items).length == 0){
        return false;
    }
    if(vbanco.length < 2 && vtipo.toLowerCase() != 'contado'){
        Modal.poner('Selecciona el banco','Vencimientos/Cobros','vbanco');
        return false;
    }

    var patron = /^(\-)?[0-9]+([\.]?[0-9]{0,})$/;
    if(!patron.test(vimpo)){
        Modal.poner("Solo se adminten números y el signo 'menos'<br>Ejemplo: -14.19 ó 452","Error en el número","vimporte");
        setTimeout(function(){
            $('#'+id).focus().select();
        },100);
        return false;
    }

    vimpo = parseFloat(vimpo).toFixed(g_decimales);

    g_vctmo[g_nvcmo] = {
        'vfecha':fechamysql(vfecha),
        'vtipo':vtipo,
        'vimporte':vimpo,
        'vbanco':vbanco[0],
        'vswift':vbanco[1]
    };

    var itemv = '';
    itemv += '<div style="clear: both;float: left;margin-right: 10px; width: 80px">'+vfecha+'</div>'+
        '<div style="float: left;margin-right: 10px; width: 60px">'+vtipo+'</div>'+
        '<div style="float: left;margin-right: 10px; width: 80px" class="text-right" title="Cuenta Banco: '+vbanco[0]+'">'+vimpo+'</div>'+
        '<div style="float: left;width: 15px;text-align: right">&nbsp;<i style="color: #900;" class="fa fa-close" onclick="borrarvcto(\''+g_nvcmo+'\')" title="Borrar Vencimiento/Cobro"></i></div>';

    $('#div-vctos').append(itemv);

    var tot = parseFloat($('#totalfactura').html());
    var subtot = 0;
    for(var j in g_vctmo){
        subtot = subtot + parseFloat(g_vctmo[j].vimporte);
    }
    var dif = tot - parseFloat(subtot);
    $('#vimporte').val((dif/1).toFixed(g_decimales)).select();

    var fespl = vfecha.split('/');
    fespl[1] = parseInt(fespl[1]) + 1;
    if(fespl[1]  > 12){
        fespl[1] = '01';
        fespl[2] = parseInt(fespl[2]) + 1;
    }else if(fespl[1] >= 2 && fespl[1] <= 9){
        fespl[1] = "0"+String(fespl[1]);
    }else{
        fespl[1] = String(fespl[1]);
    }
    var nfechav = fespl[0]+"/"+fespl[1]+"/"+fespl[2];
    $('#vfecha').val(nfechav);
    $

    g_nvcmo++;
});

function borrarvcto(id){

    delete g_vctmo[id];

    var nvt = Object.keys(g_vctmo).length;
    if(nvt < 1){
        $('#div-vctos').empty();
        $('#vimporte').val($('#totalfactura').html());
        g_vctmo = {};
        return false;
    }
    var itemv = '';
    var subtot = 0;
    for(var j in g_vctmo){
        if(!( j in g_vctmo)){
            continue;
        }

        subtot = subtot + parseFloat(g_vctmo[j].vimporte);
        itemv += '<div style="clear: both;float: left;margin-right: 10px; width: 80px">'+fechaform(g_vctmo[j].vfecha)+'</div>'+
            '<div style="float: left;margin-right: 10px; width: 60px">'+g_vctmo[j].vtipo+'</div>'+
            '<div style="float: left;margin-right: 10px; width: 80px" class="text-right" title="Cuenta Banco: '+g_vctmo[j].vbanco+'">'+g_vctmo[j].vimporte+'</div>'+
            '<div style="float: left;width: 15px;text-align: right">&nbsp;<i style="color: #900;" class="fa fa-close" onclick="borrarvcto(\''+j+'\')" title="Borrar Vencimiento/Cobro"></i></div>';
    }

    $('#div-vctos').empty().html(itemv);
    var tot = parseFloat($('#totalfactura').html());
    var dif = tot - parseFloat(subtot);
    $('#vimporte').val((dif/1).toFixed(g_decimales));

}

$('#vimporte').keyup(function(e){
    if(e.keyCode == 13){
        $('#btn-v-anadir').trigger('click');
    }
});

$('#vfecha').focus(function(){
   $(this).select();
});

function calaculartotal(){
    g_iva = {};
    g_ret = 0;
    g_req = 0;
    g_totitem = 0;
    var giva = 0;
    var reqsn = $("input[name='exentoiva']:checked").val();

    for (var i in g_items){
        var imp = 0;
        imp = parseFloat(g_items[i].cantidad) * parseFloat(g_items[i].precio);
        imp = imp - (imp * parseFloat(g_items[i].descuento / 100));
        if(!(g_items[i].iva in g_iva)){
            g_iva[g_items[i].iva] = 0;
            g_iva['bi_'+String(g_items[i].iva)] = 0;
            //g_bi_iva[g_items[i].iva] = 0;
        }
        var iva = imp * parseFloat(g_items[i].iva) / 100;
        //g_bi_iva[g_items[i].iva] = g_bi_iva[g_items[i].iva] + imp;
        g_iva['bi_'+String(g_items[i].iva)] = g_iva['bi_'+String(g_items[i].iva)] + imp;
        iva = parseFloat((iva/1).toFixed(2));
        g_iva[g_items[i].iva] = g_iva[g_items[i].iva] + parseFloat(iva.toFixed(2));
        g_totitem = g_totitem + imp;
        giva = giva + iva;
    }
    var ret = parseFloat($('#usuret').val());
    g_ret = parseFloat((g_totitem * ret / 100).toFixed(2));

    if(reqsn == 'no'){
        var req = parseFloat($('#usureq').val());
    }else{
        var req = 0;
    }

    g_req = parseFloat((g_totitem * req / 100).toFixed(2));

    g_total = g_totitem - g_ret + giva + g_req;

    return g_total;
}

$('input[name=exentoiva]').change(function(){
    var v =$(this).val();
    if(v == 'no'){
        $('#textoexento').val('');
        $('#iva').prop('readonly',false).val($('#usuiva').val());
    }else{
        $('#iva').prop('readonly',true).val('0.00');
    }
    g_trgexe = 'si';
    $('#btn-vaciaitems').trigger('click');
});

$('#btn-vaciaitems').click(function(){
    if(g_trgexe != 'si'){
        if(!confirm("Vas a borrar los elementos de la factura\n\y los vencimientos/cobros")){
            return false;
        }
    }
    g_trgexe = '';
    g_items = {};
    g_vctmo = {};
    $('#div-items').empty();
    $('#div-vctos').empty();
    $('#precio').val((0/1).toFixed(g_decimales));
    $('#concepto').val('');
    $('#totimp').val((0/1).toFixed(g_decimales));
    $('#vimporte').val((0/1).toFixed(g_decimales));
    $('#totalfactura').empty().html((0/1).toFixed(g_decimales));
    $('#vbanco').val('--');
});

$('#btn-verfactura').click(function() {
    var nitems = Object.keys(g_items).length;
    var nvctos = Object.keys(g_vctmo).length;
    var siniva = $('input[name=exentoiva]:checked').val();
    var lentxt = $('#textoexento').val().length;
    if(nitems < 1){
        Modal.poner('Debes de poner por lo menos un producto','Factura vacia','cantidad');
        return false;
    }
    if(nvctos < 1){
        Modal.poner('Debes de poner por lo menos Vencimiento/Cobro','Vencimientos/Cobros','vfecha');
        return false;
    }
    if(siniva == 'si' && lentxt == 0){
        Modal.poner('Debes de poner el texto de exención','Error','textoexento');
        return false;
    }
    var datos = {"items": g_items,
        "usufechafac":fechamysql($('#usufechafac').val()),
        "factcif":$('#factcif').val(),
        "venci":g_vctmo};

    var lnom = $('#factnombre').html().trim();

    if(lnom < 2){
        $('#factcif').trigger('focusout');
    }

    $.ajax({
        type:'post',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:'/validardatosfactura',
        data:JSON.stringify(datos),
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'si':
                    facturacion();
                    break;
                case 'no':
                    switch (data['id']) {
                        case 'usufechafac':
                            $('#' + data['id']).errorForm();
                            Modal.poner('Fecha de la factura con errores', 'Error en los datos', 'usufechafac');
                            break;
                        case 'factcif':
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
                            Modal.poner('Solo números, un  signo menos y un punto<br>Revisa el dato '+data['id'].toUpperCase()+' de un PRODUCTO facturado', 'Formato numérico', data['id']);
                            break;
                        case 'venci':
                            $('#vimporte').errorForm();
                            Modal.poner('Tiene que haber un Cobro/Vencimiento', 'Vencimientos/Cobros', 'vimporte');
                            break;
                        case 'vfecha':
                            $('#' + data['id']).errorForm();
                            Modal.poner('La fecha de un vencimiento es errónea', 'Vencimientos/Cobros', 'vfecha');
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



function facturacion() {
    var timefac = new Date();
    var segs = "R"+String(timefac.getTime());
    var idemp = $('#sel-empresa').val();
    var datosempresa = {};
    datosempresa = {"retencion":g_ret,
        "recargo":g_req,
        "fechafact":fechamysql($('#usufechafac').val()),
        "num_fact":'',
        "importe":g_totitem,
        "almacen":$("input[name='almacen']:checked").val(),
        "idempresa":idemp,
        "reload":segs,
        "exento_iva":$("input[name='exentoiva']:checked").val(),
        "texto_exento":$('#textoexento').val(),
        "total":g_total
    };
    var cliente = {};

    cliente = {"cif":$('#factcif').val(),
        "nombre":$('#factnombre').html(),
        "direccion":$('#factdirec').html(),
        "formapago":$('#factformapago').html(),
        "obserfactura":$('#fact-obser').val()};

    var datos = {};

    datos['facturaitems'] = JSON.stringify(g_items);
    datos['facturaiva'] = JSON.stringify(g_iva);
    datos['facturadatos'] = JSON.stringify(datosempresa);
    datos['facturavenci'] = JSON.stringify(g_vctmo);
    datos['facturacliente'] = JSON.stringify(cliente);

    $.ajax({
        type:'post',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:'/hacerfactura',
        data:JSON.stringify(datos),
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'si':
                    var url = "/factura/"+data['numero_fact']+"/"+idemp;
                    var mipop = window.open(url,data['numero_fact'],"width=800,height=600,resizable=yes,top=30,left=100,menubar=yes,location=no,scrollbars=yes");
                    mipop.focus();
                    self.location.reload(true);
                    break;
                case 'no':
                    Modal.poner('Error en los datos, notificarlo al programador','FACTURA','importe');
                    break;
                case 'sesion':
                    window.self.location.assign('/');
                    break;


            }
        }
    });
}

$('#btn-factalba').click(function(){
    var cif = $('#factcif').val();
    if(cif.length == 0){
        Modal.poner('Selecciona un cliente','Clientes','factcif');
        return false;
    }
    var url = "/veralbaranes/"+cif;
    var popalba = window.open(url,"veralbaranes","width=800,height=600,resizable=yes,top=30,left=100,menubar=yes,location=no,scrollbars=yes");
    popalba.focus();
});

//----------------------Cambio de logo

$('#cambialogo').change(function(){
    var datos = {};
    datos['logo'] = $(this).val();
    datos['idemp'] = $('#sel-empresa').val();
    $.ajax({
        type:'post',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:'/cambiologo',
        data:JSON.stringify(datos),
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'si':
                    $('#logoempresa').prop('src',datos['logo']);
                    break;
                case 'no':
                    Modal.poner('Error en los datos, notificarlo al programador','LOGO','cambiologo');
                    break;
                case 'sesion':
                    window.self.location.assign('/');
                    break;


            }
        }
    });

});

$('#usuiva').blur(function() {
    var da = $(this).val();
    var id = $(this).prop('id');
    var exe = $("input[name='exentoiva']:checked").val();
    if(decimal2(da,id)){
        if(exe == 'no'){
            $('#iva').val($(this).val());
        }
    }

})

//-------------------------------------


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

$('#factcif').focusout(function(){
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
                        //Modal.poner("Ese cliente no existe",'Error en el cliente','factcif');
                        $('#dialog').dialog(g_optdialog).dialog('open');
                        $('#webcliente').prop("src","/clientes/popup")
                       /* var popcli = window.open("/clientes/popup","anadirclientes","width=800,height=600,resizable=yes,top=30,left=100,menubar=yes,location=no,scrollbars=yes");
                        popcli.focus();*/
                        break;
                    }
                    $('#factnombre').empty().html(data['nombre']);
                    $('#obsercli').empty().html(data['obser']);
                    var dire = data['direccion']+"\n"+'Telf. '+data['telf']+"\n"+data['cp']+' - '+priMay(data['poblacion'])+"\n"+priMay(data['provincia'])+' ('+priMay(data['pais'])+')';
                    dire = dire.replace(/\n/g,"<br>");
                    $('#factdirec').empty().html(dire);
                    $('#factdesc').empty().html(data['descuento']);
                    $('#factformapago').empty().html(data['forma_pago']);
                    $('#descuento').val(data['descuento']);
                    //$('#descuento').val(data['descuento'])
                    break;
                case 'no':
                    Modal.poner('No está disponible el servicio','Error en la Red','factcif');
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
                    g_vctmo = {};
                    $('#div-items').empty();
                    $('#div-vctos').empty();
                    $('#cambialogo').empty().html(data['logos']);
                    $('#usunombre').empty().html(priMay(data['nombre']));
                    $('#usucif').empty().html(data['cif'].toUpperCase());
                    if(data['exento_iva'] == 'si'){
                        $('#exentosi').prop('checked', true);
                        $('#iva').prop({'placeholder':data['tipo_iva'],'title':data['tipo_iva'],'readonly':true}).val('0.00');
                        $('#textoexento').val(data['texto_exento']);
                        var txe = '<option value="'+data['texto_exento']+'">'+data['texto_exento']+'</option>'
                        $('#txtexe').empty().html(txe);
                    }else{
                        $('#exentono').prop('checked', true);
                        $('#iva').prop({'placeholder':data['tipo_iva'],'title':data['tipo_iva'],'readonly':false}).val(data['iva']);
                        $('#textoexento').val('');
                        var txe = '<option value="'+data['texto_exento']+'">'+data['texto_exento']+'</option>'
                        $('#txtexe').empty().html(txe);
                    }
                    var numerofac = data['prefijo_numfac']+data['numero_fac']+data['sufijo_numfac'];
                    $('#usunumfac').val(numerofac);
                    $('#factcif').val('');
                    $('#obsercli').html('');
                    $('#factnombre').html('');
                    $('#factdirec').html('');
                    $('#usufechafac').val(data['fecha_factura']);
                    $('#usutipoiva').empty().html(data['tipo_iva']+":");
                    $('#usuiva').val(data['iva']);
                    $('#usuret').val(data['retencion']);
                    $('#usureq').val(data['req_equi']);
                    $('#logoempresa').prop('src',data['logo']);
                    $('#item-tipoiva,#tipoexento').empty().html(data['tipo_iva']);
                    $('#simb-moneda,#simb-moneda2').empty().html(data['cf_mo_simbolo']);
                    g_decimales = data['cf_decimales'] ;
                    $('#precio').val((0/1).toFixed(g_decimales));
                    $('#concepto').val('');
                    var totimp = 0.00;
                    $('#totimp').val((totimp/1).toFixed(g_decimales));
                    var venci = 0.00;
                    $('#vimporte').val((venci/1).toFixed(g_decimales));
                    var total = '0.00';
                    $('#totalfactura').empty().html((total/1).toFixed(g_decimales));
                    $('#vbanco').empty().html(data['bancos']);
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
