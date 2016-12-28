$('#btn-anadircli').click(function(){
    var datos = $('#frm-acliente').serializeArray();
    for(var i in datos){
        var name = datos[i].name;
        var tipo = $('input[name='+name+']').data('tipo');
        datos[i]['tipo'] = tipo;
   }

    $.ajax({
        type:'put',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:'/anadircliente',
        data:JSON.stringify(datos),
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'si':
                    if(parent.$('#dialog').length > 0){
                        parent.$('#dialog').dialog('close');
                        parent.Modal.poner("Cliente añadido correctamente","Clientes");
                    }else{
                        Modal.poner("Cliente añadido correctamente","Clientes");
                        $('#frm-acliente')[0].reset();
                        self.location.reload(true);
                    }

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

                    /*switch (data['id']) {
                        case 'nombre':
                            $('#' + data['id']).errorForm();
                            Modal.poner('Hay errores en este campo o está vacio', 'Clientes', data['id']);
                            break;
                        case 'cif':
                            $('#' + data['id']).errorForm();
                            Modal.poner('Revisa el CIF', 'Error en los datos', data['id']);
                            break;
                        case 'direccion':
                            $('#' + data['id']).errorForm();
                            Modal.poner('El nombre tiene errores o está vacio', 'Clientes', data['id']);
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
                    }*/
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

