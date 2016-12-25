$('#btn-anadircli').click(function(){
    var datos = {};
    datos = $('#frm-acliente').serializeArray();

   /* $.ajax({
        type:'post',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:'/anadircliente',
        data:JSON.stringify(datos),
        success: function(data){
            console.log(data);

            switch(data['ok']){

                case 'sis':
                    var url = "/factura/"+data['numero_fact']+"/"+idemp;
                    var mipop = window.open(url,data['numero_fact'],"width=800,height=600,resizable=yes,top=30,left=100,menubar=yes,location=no,scrollbars=yes");
                    mipop.focus();
                    self.location.reload(true);
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
    });*/
});
