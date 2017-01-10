var g_filesize = 100; //Kb
var g_base64 = '';

function ponformu(dato,f){
    var datos = dato;
    var idd = '';
    $('#'+f).find(':input').each(function(){

        switch($(this).prop('type')){

            case 'radio':
            case 'checkbox':
                idd = $(this).prop('name');
                $('input[name="'+idd+'"]').each(function(){
                    $(this).prop('checked',false);
                    if($(this).val() == datos[idd]){
                        $(this).prop('checked',true);
                    }
                })
                break;
            case 'button':

                break;
            default:
                idd = $(this).prop('name');
                if(datos[idd] != undefined) {
                    if(datos[idd].indexOf('0000-00-00')>=0 || datos[idd].indexOf('00/00/0000')>=0){
                        datos[idd] = '';
                    }
                    $('input[name="'+idd+'"]').val(datos[idd]);
                }else{
                    //alert(datos)
                }
                break;
        }
    });
    $('#'+f).find('textarea').each(function(){
        idd = $(this).prop('name');
        $('textarea[name="'+idd+'"]').val(datos[idd])

    });
}

$('#datosbusemp').keyup(function(){
    var val = $(this).val();
    if(val.length < 4){
        limpiarforms();
        $('#resbuscaemp').empty();
        return false;
    }

    var campo = $('input[name=buscaempre]:checked').val();
    var datos = {};
    datos['campo'] = campo;
    datos['buscar'] = val;
    datos['tipobusca'] = 'like';
    datos['tabla'] = 'empresas';

    $.ajax({
        type:'post',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:'/buscaempre',
        data:JSON.stringify(datos),
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'si':
                    if(data['nl'] < 1){
                        $('#resbuscaemp').empty().html('<option value="--"><h3>No hay coincidencias</h3></option>');
                        limpiarforms();
                    }else{
                        $('#resbuscaemp').empty().html(data['datos']);
                    }

                    break;
                case 'no':
                    $('#' + data['id']).errorForm();
                    Modal.poner('Hay errores en este campo o está vacio', 'Empresas', data['id']);
                    break;
                case 'nob':
                    Modal.poner('El CIF está duplicado o hay problemas con el servidor', 'Empresas');
                    break;
                case 'sesion':
                    window.self.location.assign('/');
                    break;


            }
        }
    });
});

$('input[name=buscaempre]').change(function(){
    $('#resbuscaemp').empty();
});

$('#resbuscaemp').change(function(){
    var datos = {};
    datos['id'] = $(this).val();
    $.ajax({
        type:'get',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:'/buscaempresa/'+datos['id'],
        data:'',
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'si':

                    ponformu(data['empresa'],'frm-modiempresa1');
                    ponformu(data['datos'],'frm-modiempresa2');
                    ponformu(data['config'],'frm-modiempresa3');
                    $('#listalogos').empty().html(data['logos']);
                    $('#listabancos').empty().html(data['bancos']);
                    $('#frm-modiempresa5')[0].reset();
                    break;
                case 'no':
                    Modal.poner('Hay errores en este campo o está vacio', 'Empresas', data['id']);
                    break;
                case 'nob':
                    Modal.poner('El CIF está duplicado o hay problemas con el servidor', 'Empresas');
                    break;
                case 'sesion':
                    window.self.location.assign('/');
                    break;


            }
        }
    });
});


function editabanco(banco,cuenta,swift,ac){
    $('#banco').val(banco);
    var cc = cuenta.split('');
    $('#numero_cuenta').val(cc[0]+cc[1]+cc[2]+cc[3]);
    $('#cc2').val(cc[4]+cc[5]+cc[6]+cc[7]);
    $('#cc3').val(cc[8]+cc[9]+cc[10]+cc[11]);
    $('#cc4').val(cc[12]+cc[13]+cc[14]+cc[15]);
    $('#cc5').val(cc[16]+cc[17]+cc[18]+cc[19]);
    $('#cc6').val(cc[20]+cc[21]+cc[22]+cc[23]);
    $('#swift').val(swift);
    if(ac == 'si'){
        $('#sino1').prop('checked',true);
    }else{
        $('#sino2').prop('checked',true);
    }
    $('input[name=activo]').val(ac);
}

function eliminabanco(cc){
    var datos = {};
    datos['numero_cuenta'] = cc;
    datos['id_empresa'] = $('#id').val();


    $.ajax({
        type:'delete',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:'/borrabanco',
        data:JSON.stringify(datos),
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'si':
                    Modal.poner("Banco borrado correctamente","Empresas");
                    $('#listabancos').empty().html(data['bancos']);
                    //self.location.reload(true);
                    break;
                case 'no':
                    if(data['id'] == 'id'){
                        Modal.poner('No hay seleccionada una empresa', 'Empresas', data['id']);
                        break;
                    }
                    $('#' + data['id']).errorForm();
                    Modal.poner('Hay errores en este campo o está vacio', 'Empresas', data['id']);
                    break;
                case 'nob':
                    Modal.poner('El CIF está duplicado o hay problemas con el servidor', 'Empresas');
                    break;
                case 'sesion':
                    window.self.location.assign('/');
                    break;


            }
        }
    });
}

$('#btn-modibanco').click(function(){
    var cc = $('#numero_cuenta').val()+$('#cc2').val()+$('#cc3').val()+$('#cc4').val()+$('#cc5').val()+$('#cc6').val();
    //$('#numero_cuenta').val(cc);
    var datos = {};
    datos['banco'] = $('#frm-modiempresa5').serializeArray();
    for(var i in datos['banco']){
        var name = datos['banco'][i].name;
        var tipo = $('input[name='+name+']').data('tipo');
        datos['banco'][i]['tipo'] = tipo;
        datos['banco'][i]['id'] = name;
        delete datos['banco'][i]['name'];
        if(datos['banco'][i]['id'] == 'numero_cuenta'){
            datos['banco'][i]['value'] = cc;
        }
        if(datos['banco'][i]['id'] == 'activa'){
            datos['banco'][i]['id'] = 'activo';
        }
    }
    var k = Object.keys(datos['banco']).length;
    datos['banco'][k] = {'id':'id_empresa',
                        "tipo":'numero',
                        "value":$('#id').val()};

    $.ajax({
        type:'put',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:'/modificabanco',
        data:JSON.stringify(datos),
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'si':
                    Modal.poner("Banco modificado/añadido correctamente","Empresas");
                    $('#listabancos').empty().html(data['bancos']);
                    //self.location.reload(true);
                    break;
                case 'no':
                    if(data['id'] == 'id'){
                        Modal.poner('No hay seleccionada una empresa', 'Empresas', data['id']);
                        break;
                    }
                    $('#' + data['id']).errorForm();
                    Modal.poner('Hay errores en este campo o está vacio', 'Empresas', data['id']);
                    break;
                case 'nob':
                    Modal.poner('El CIF está duplicado o hay problemas con el servidor', 'Empresas');
                    break;
                case 'sesion':
                    window.self.location.assign('/');
                    break;


            }
        }
    });
});


$('#logo').change(function(){
    $('#listafiles').empty();
    g_base64 = '';
    var size = parseInt(parseInt(this.files[0].size)/1000);
    var name = this.files[0].name;
    if(size > g_filesize){
        Modal.poner("El tamaño maximo para el logo es de "+g_filesize+" Kb", "Empresas", "logo")
        $('#verlogo').prop("src", "");
        $(this).val('');
        return false;
    }
    var doc = $(this).val().split(".");
    switch (doc[1].toLowerCase()) {
        case 'jpg':
        case 'jpeg':
        case 'gif':
        case 'png':
        case 'svg':
            readImage(this);
            $('#listafiles').html(name+' - '+size+' Kb');
            break;
        default:
            Modal.poner("Solo se admiten archivos con extensión JPG,GIF,PNG,SVG", "Empresas", "logo")
            $('#verlogo').prop("src", "");
            $(this).val('');
            $('#listafiles').empty();
            g_base64 = '';
            break;
    }
});

function readImage(input) {
    if ( input.files && input.files[0] ) {
        var FR= new FileReader();
        FR.onload = function(e) {

            //$('iframe[name=verlogo]').prop( "src", e.target.result );
            $('#verlogo').prop("src",e.target.result);
            g_base64 = e.target.result;
        };
        FR.readAsDataURL( input.files[0] );
    }
}

$('#btn-anadirlogo').click(function() {
    var idemp = $('#id').val();
    if(idemp.length < 1){
        Modal.poner("Selecciona una empresa","Empresas","id");
        return false;
    }
    var datos = {}
    datos['logo'] = {'base64':g_base64,
        'nombre': $('input[name=logo]').val(),
        'id_empresa':idemp};

    $.ajax({
        type:'post',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:'/nuevologo',
        data:JSON.stringify(encodeURIComponent(datos)),
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'si':
                    Modal.poner("Logo añadido correctamente","Empresas");
                    $('#listalogos').empty().html(data['logos']);
                    //self.location.reload(true);
                    break;
                case 'no':
                    if(data['id'] == 'id'){
                        Modal.poner('No hay seleccionada una empresa', 'Empresas', data['id']);
                        break;
                    }
                    $('#' + data['id']).errorForm();
                    Modal.poner('Hay errores en este campo o está vacio', 'Empresas', data['id']);
                    break;
                case 'nob':
                    Modal.poner('El CIF está duplicado o hay problemas con el servidor', 'Empresas');
                    break;
                case 'sesion':
                    window.self.location.assign('/');
                    break;


            }
        }
    });
});


function limpiarforms(){
    $('#frm-modiempresa1')[0].reset();
    $('#frm-modiempresa2')[0].reset();
    $('#frm-modiempresa3')[0].reset();
    $('#frm-modiempresa4')[0].reset();
    $('#frm-modiempresa5')[0].reset();
    $('#verlogo').prop('src','');
    //$('#datosbusemp').val('');
    $('#listafiles').empty();
    $('#listabancos').empty();
    $('#listalogos').empty();
    g_base64 = '';
}

function borrarlogo(id,nombre){
    var datos = {};
    datos['id'] = id;
    datos['nombre'] = nombre;
    datos['id_empresa'] = $('#id').val();


    $.ajax({
        type:'delete',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:'/borralogo',
        data:JSON.stringify(datos),
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'si':
                    Modal.poner("Logo borrado correctamente","Empresas");
                    $('#listalogos').empty().html(data['logos']);
                    //self.location.reload(true);
                    break;
                case 'no':
                    if(data['id'] == 'id'){
                        Modal.poner('No hay seleccionada una empresa', 'Empresas', data['id']);
                        break;
                    }
                    $('#' + data['id']).errorForm();
                    Modal.poner('Hay errores en este campo o está vacio', 'Empresas', data['id']);
                    break;
                case 'nob':
                    Modal.poner('El CIF está duplicado o hay problemas con el servidor', 'Empresas');
                    break;
                case 'sesion':
                    window.self.location.assign('/');
                    break;


            }
        }
    });
}

$('#btn-modiempresa').click(function(){
    var datos = {};
    datos['empresa'] = $('#frm-modiempresa1').serializeArray();
    datos['datos_iva'] = $('#frm-modiempresa2').serializeArray();
    datos['config'] = $('#frm-modiempresa3').serializeArray();

    for(var i in datos['empresa']){
        var name = datos['empresa'][i].name;
        var tipo = $('input[name='+name+']').data('tipo');
        datos['empresa'][i]['tipo'] = tipo;
        datos['empresa'][i]['id'] = name;
        delete datos['empresa'][i]['name'];
   }
    for(i in datos['datos_iva']){
         name = datos['datos_iva'][i].name;
         tipo = $('input[name='+name+']').data('tipo');
        datos['datos_iva'][i]['tipo'] = tipo;
        datos['datos_iva'][i]['id'] = name;
        delete datos['datos_iva'][i]['name'];
    }
    for(i in datos['config']){
        name = datos['config'][i].name;
        tipo = $('input[name='+name+']').data('tipo');
        datos['config'][i]['tipo'] = tipo;
        datos['config'][i]['id'] = name;
        delete datos['config'][i]['name'];

    }

    $.ajax({
        type:'put',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:'/modificaempresa',
        data:JSON.stringify(datos),
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'si':
                    Modal.poner("Empresa modificada correctamente","Empresas");
                    //self.location.reload(true);
                    break;
                case 'no':
                    if(data['id'] == 'id'){
                        Modal.poner('No hay seleccionada una empresa', 'Empresas', data['id']);
                        break;
                    }
                    $('#' + data['id']).errorForm();
                    Modal.poner('Hay errores en este campo o está vacio', 'Empresas', data['id']);
                    break;
                case 'nob':
                    Modal.poner('El CIF está duplicado o hay problemas con el servidor', 'Empresas');
                    break;
                case 'sesion':
                    window.self.location.assign('/');
                    break;


            }
        }
    });
});


$(document).ready(function(){

    limpiarforms();
    g_ancho = $(window).width();
    var disp = navigator.userAgent.toLowerCase();
    if(disp.search(/iphone|ipod|ipad|android|sailfish|mobile/) > -1){
        g_navega = 'movil';
        //$('#llamada').css('text-align','left');

    }else{
        g_navega = 'pc';
        //$('#llamada').css('text-align','right');
    }

    if(g_ancho <= 850){
        $('#emp-col1,#emp-col2,#emp-col3').css('width','50%');
    }
    window.addEventListener('resize',function(){
        g_ancho = $(window).width();
        if(g_ancho >= 851){
            $('#emp-col1').css('width','33%');
            $('#emp-col2').css('width','32%');
            $('#emp-col3').css('width','32%');
        }else{
            $('#emp-col1,#emp-col2,#emp-col3').css('width','50%');
        }
    });




});

