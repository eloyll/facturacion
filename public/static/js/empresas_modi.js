var g_filesize = 100; //Kb
var g_base64 = '';

$('#datosbusemp').keyup(function(){
    var val = $(this).val();
    if(val.length < 4){
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



$('#logo').change(function(){
    $('#listafiles').empty();
    g_base64 = '';
    var size = parseInt(this.files[0].size)/1000;
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

$('#btn-limpiaremp').click(function(){
    $('#frm-empresa1')[0].reset();
    $('#frm-empresa2')[0].reset();
    $('#frm-empresa3')[0].reset();
    $('#frm-empresa4')[0].reset();
    $('#verlogo').prop('src','');
    $('#listafiles').empty();
    g_base64 = '';
});

$('#btn-anadiremp').click(function(){
    var datos = {};
    datos['empresa'] = $('#frm-empresa1').serializeArray();
    datos['datos_iva'] = $('#frm-empresa2').serializeArray();
    datos['config'] = $('#frm-empresa3').serializeArray();
    datos['logo'] = {'base64':g_base64,
                    'nombre': $('input[name=logo]').val()};
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
        url:'/anadirempresa',
        data:JSON.stringify(datos),
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'si':
                    Modal.poner("Empresa añadida correctamente","Empresas");
                    self.location.reload(true);

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

