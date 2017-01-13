
function limpiarforms(){
    $('#frm-modicliente')[0].reset();
    $('#fecha_alta').empty();
}

$('#btn-limpiarcli').click(function(){
    limpiarforms();
});


$('#datosbuscar').keyup(function(){
    var val = $(this).val();
    if(val.length < 4){
        limpiarforms();
        $('#resbuscar').empty();
        return false;
    }

    var campo = $('input[name=buscarcli]:checked').val();
    var datos = {};
    datos['campo'] = campo;
    datos['buscar'] = val;
    datos['tipobusca'] = 'like';
    datos['tabla'] = 'clientes';
    datos['idusu'] = $('#idusu').val(); console.log(datos)

    $.ajax({
        type:'get',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:'/buscacli',
        data:(datos),
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'si':
                    if(data['nl'] < 1){
                        $('#resbuscar').empty().html('<option value="--"><h3>No hay coincidencias</h3></option>');
                        limpiarforms();
                    }else{
                        $('#resbuscar').empty().html(data['datos']);
                    }

                    break;
                case 'no':
                    $('#' + data['id']).errorForm();
                    Modal.poner('Hay errores en este campo o está vacio', 'Empresas', data['id']);
                    break;

                case 'sesion':
                    window.self.location.assign('/');
                    break;

            }
        }
    });
});

$('#resbuscar').change(function(){
    var datos = {};
    datos['id'] = $(this).val();
    $.ajax({
        type:'get',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:'/buscarcliente/'+datos['id'],
        data:'',
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'si':

                    ponformu(data,'frm-modicliente');
                    $('#fecha_alta').empty().html(data['fecha_alta'])

                    break;
                case 'no':
                    Modal.poner('Hay errores en este campo o está vacio', 'Empresas', data['id']);
                    break;

                case 'sesion':
                    window.self.location.assign('/');
                    break;


            }
        }
    });
});











$('#btn-modicliente').click(function(){
    var datos = $('#frm-modicliente').serializeArray();
    for(var i in datos){
        var name = datos[i].name;
        var tipo = '';
        tipo = $('input[name='+name+']').data('tipo');
        if(tipo == undefined){
            datos[i]['tipo'] = 'noval';
        }else{
            datos[i]['tipo'] = tipo;
        }
        datos[i]['id'] = name;
        delete datos[i]['name'];

   }

    $.ajax({
        type:'put',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:'/modificarcliente',
        data:JSON.stringify(datos),
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'si':
                    Modal.poner("Cliente modificado correctamente","Clientes");
                    $('#resbuscar').empty();
                    limpiarforms();
                    break;
                case 'no':
                    if(data['id'] == 'cif'){
                        $('#' + data['id']).errorForm();
                        if(data['nombre'] == undefined){
                            var campo = "";
                        }else{
                            var campo = "("+data['nombre']+")";
                        }
                        Modal.poner("Hay errores en este campo o está duplicado <br>"+campo, 'Clientes', data['id']);
                    }else{
                        $('#' + data['id']).errorForm();
                        Modal.poner('Hay errores en este campo o está vacio', 'Clientes', data['id']);
                    }
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
        $('#cli-col1,#cli-col2,#cli-col3').css('width','50%');
    }
    window.addEventListener('resize',function(){
        g_ancho = $(window).width();
        if(g_ancho >= 851){
            $('#cli-col1').css('width','33%');
            $('#cli-col2').css('width','32%');
            $('#cli-col3').css('width','32%');
        }else{
            $('#cli-col1,#cli-col2,#cli-col3').css('width','50%');
        }
    });



});

