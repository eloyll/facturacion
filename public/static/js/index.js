var g_botones = [];
g_botones[0] =  "";
g_botones[1] =  "";
g_botones[2] =  "";
g_botones[3] =  "";
g_botones[4] =  "";
g_botones[5] =  "";
g_botones[6] =  "";
g_botones[7] =  "";
g_botones[8] =  "";
g_botones[9] =  "";

var g_pass = [];
g_pass[0] = "";
g_pass[1] = "";
g_pass[2] = "";

var g_clv = {};
g_clv[0] = '*';
g_clv[1] = '*';
g_clv[2] = '*';

g_fecha = new Date();
g_dia = String(g_fecha.getDate());
if(g_dia.length == 1){
    g_dia = "0"+g_dia;
}
g_mes = String(g_fecha.getMonth()+1);
if(g_mes.length == 1){
    g_mes = "0"+g_mes;
}
g_ano = g_fecha.getFullYear();
g_ano15 = String(g_fecha.getFullYear()+15).substring(2);
g_hoy = String(g_dia)+"/"+String(g_mes)+"/"+String(g_ano);

var g_idmovermodal = '';
var g_foco = '';
var g_navega = '';
var g_titulomodal = '';

Modal = {
    poner: function(txt,tit,foco){
        if(g_navega == 'movil'){
            alert(txt);
            Moverto.idelem(g_idmovermodal);
            g_idmovermodal = '';
        }else {
            var tope = $(window).scrollTop();
            $('#mimodal').css('top', tope + "px");
            //$('body,html').css('overflow', 'hidden');
            $('#mimodal').fadeIn(300);
            $('#texto-modal').empty().html(txt);
            if (tit != undefined) {
                $('#titulo-modal').empty().html(tit);
            }
        }
        g_foco = foco;
    },
    sacar: function(){

        $('#mimodal').fadeOut(300,function(){
            //$('body,html').css('overflow','auto');
            $('#mimodal').css('top',"0");
            $('#titulo-modal').empty().html(g_titulomodal);
            $('#texto-modal').empty();
            if(g_idmovermodal.length > 0){
                Moverto.idelem(g_idmovermodal);
            }
            g_idmovermodal = '';
        });
        if(g_foco != undefined){
            $('#'+g_foco).focus();
        }
        g_foco = '';

    }
};

Moverto = {
    idelem: function(id){
        var scrl = parseInt($("#"+id).offset().top)-70;
        $('html,body').animate({scrollTop: scrl},'slow');

    }
};

$.fn.SerializaForm = function () {
    var datos = {};
    //var a = this.serializeArray();
    var id = this.prop('id');

    g_radio = 'dd';

    $('#'+id+" :input").each(function(){
        if(this.type == 'button' ) {

            return true;
        }else if(this.type == 'checkbox' && !$(this).is(':checked')){

            return true;
        }else if(this.type == 'radio'){
            if(g_radio != this.name) {
                if (!$(this).is(':checked')) {
                    datos[this.name] = {
                        'value': '',
                        'tipo': 'noval',
                        'id': ''
                    };
                } else {
                    datos[this.name] = {
                        'value': $(this).val(),
                        'tipo': $(this).data('tipo'),
                        'id': $(this).prop('id')
                    };
                    g_radio = this.name;
                }
            }else{
                return true;
            }
        }else {
            if($(this).data('tipo') == 'fecha'){
                var fe = fechamysql($(this).val());
            }else{
                var fe = $(this).val();
            }
            datos[this.name] = {'value':fe,
                'tipo':$(this).data('tipo'),
                'id':$(this).prop('id')};
        }

    });
    /*$.each(a, function () {
     if (o[this.name] !== undefined) {
     if (!o[this.name].push) {
     o[this.name] = [o[this.name]];
     }
     o[this.name].push(this.value || '');
     } else {
     o[this.name] = this.value || '';
     }
     });*/
    return JSON.stringify(datos);
};

$.fn.errorForm = function(){
    var ide = this.prop('id');
    var col = $("#"+ide).css("color");
    $("#"+ide).css({"background-color":"rgba(153,0,0,0.2)","color":"#000"});
    //Moverto.idelem(ide);
    g_idmovermodal = ide; // cuando se cierre la modal el scroll se pone en el elemento
    //Modal.poner('Este campo contiene errores<br>O no puede estar vacío','Validación formulario',ide);
    this.focus(function(){
        $("#"+ide).css({"background-color":"#FFF","color":col})
    });

    /* setTimeout(
     function(){
     $("#"+ide).css("background-color","#FFF");
     },3000);*/
};


function fechamysql(f){
    if(f.length == 0){
        return f ;
    }
    var f1 = f.substring(0,2);
    var f2 = f.substring(3,5);
    var f3 = f.substring(6);

    var fm = String(f3)+'-'+String(f2)+'-'+String(f1);

    return fm;
}
function fechaform(f){
    var fe = f.split('-');
    var ff = String(fe[2])+'/'+String(fe[1])+'/'+String(fe[0]);

    return ff;
}

$('.dec2').blur(function(ev){
    var da = $(this).val();
    var id = $(this).prop('id');
    if(isNaN(da)){
        Modal.poner('Debe de ser un número con 2 decimales','Error en este campo');
        ev.preventDefault();
        setTimeout(function(){
            $('#'+id).focus();
            $('#'+id).select();
        },100);


        return;
    }else{
        var das = parseFloat(da).toFixed(2);
        if(das == 'NaN'){
            das = '0.00';
        }
        $(this).val(das);
    }
});
$('.dec0').blur(function(ev){
    var da = $(this).val();
    var id = $(this).prop('id');
    if(isNaN(da)){
        Modal.poner('Debe de ser un número sin decimales','Error en este campo');
        ev.preventDefault();
        setTimeout(function(){
            $('#'+id).focus();
            $('#'+id).select();
        },100);


        return;
    }else{
        var das = parseInt(da).toFixed(0);
        if(das == 'NaN'){
            das = '0';
        }
        $(this).val(parseInt(das));
    }
});

$('#bt-cerrar-modal').click(function(){
    Modal.sacar();
});




$('.btn-pass').click(function(){
    var cl = $(this).prop('value');
    if(g_clv[0] == "*"){
        g_clv[0] = cl;
        $('#pass-'+String(g_pass[0])).html('<span style="color: #3371c8;"><i class="fa fa-asterisk"></i></span>');

    }else if(g_clv[1] == "*"){
        g_clv[1] = cl;
        $('#pass-'+String(g_pass[1])).html('<span style="color: #3371c8;"><i class="fa fa-asterisk"></i></span>');

    }else if(g_clv[2] == "*"){
        g_clv[2] = cl;
        $('#pass-'+String(g_pass[2])).html('<span style="color: #3371c8;"><i class="fa fa-asterisk"></i></span>');
        logearse();

    }
});

function btnrand(){
    g_botones = [];
    for (var i=0;i<=9;i++){
       // var rnd = Math.floor(Math.random() * 10) + 1;
        var rnd = Math.floor(Math.random()*10) ;
        while(g_botones.indexOf(rnd) > -1 ){
            rnd = Math.floor(Math.random()*10);
        }

        g_botones[i]  = rnd;
        $('#btn-'+String(i)).prop('value',String(rnd));
    }
}

function passrand(){
    for (var i=1;i<=6;i++){

        $('#pass-'+String(i)).empty().html('<i class="fa fa-circle">');
    }

    for (var i=0;i<=2;i++){
        $('#pass-'+String(g_pass[i])).empty();
        g_clv[i] = '*';
    }

    var txt = 'Introduce los números de tu clave que ocupan las posiciones <span style="color:#000">'+String(g_pass[0])+', '+String(g_pass[1])+'</span> y <span style="color:#000">'+String(g_pass[2])+'</span>';
    $('#pass-txt').empty().html(txt)

}

$('#btn-borra-clave').click(function(){
    for(var i=0;i<=2;i++){
        g_clv[i] = '*';
    }
    for(var i=0;i<=2;i++){
        $('#pass-'+String(g_pass[i])).empty();
    }

});

$('#dni').keyup(function(){
   if($(this).val().length <= 8){
       $('#ver-rand').css('visibility', 'hidden');
       return false;
   }else{
       /*if($('#ver-rand').css('visibility') == 'visible'){
           return false;
       }*/
       var datos = {};
       datos['usuario'] = $('#usuario').val();
       datos['dni'] = $('#dni').val();
       $.ajax({
           type:'POST',
           dataType:'json',
           headers:{'Content-Type':'application/json'},
           url:'/usuario',
           data:JSON.stringify(datos),
           success: function(data){
               console.log(data);
               btnrand();
               g_pass = [];
               g_pass[0] = data[0]+1;
               g_pass[1] = data[1]+1;
               g_pass[2] = data[2]+1;
               console.log(g_pass);
               passrand();
               $('#ver-rand').css({opacity: 0.0, visibility: "visible"}).animate({'opacity': 1},500);
               switch(data['ok']){

                   case 'si':
                       //window.self.location.assign("/main");

                       break;
                   case 'no':

                       break;


               }
           }
       });
   }
});

function logearse(){

    var datos = {};
    datos['usuario'] = $('#usuario').val();
    datos['dni'] = $('#dni').val();
    datos['clave'] = String(g_clv[0])+ String(g_clv[1])+ String(g_clv[2]);

    if(datos['usuario'].length < 4){
        Modal.poner('Longitud del campo errónea (4 a 15 caracteres)','Error en USUARIO');
        $('#btn-borra-clave').trigger('click');

        return;
    }
    if(datos['dni'].length < 7){
        Modal.poner('Longitud del campo errónea (8 caracteres)','Error en el DNI/NIE');
        $('#btn-borra-clave').trigger('click');

        return;
    }
console.log(datos)
    $.ajax({
        type:'POST',
        dataType:'json',
        headers:{'Content-Type':'application/json'},
        url:'/login',
        data:JSON.stringify(datos),
        success: function(data){
            console.log(data);
            switch(data['ok']){

                case 'si':
                    window.self.location.assign("/main");

                    break;
                case 'no':
                    //$('#usuario').val('');
                    //$('#dni').val('');
                    //$('#ver-rand').fadeOut('slow');
                    $('#dni').trigger('keyup');
                    Modal.poner(data['msg'],'Error al conectarse','dni');
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


//convertir fechas de 8 ddigitos

$('.fecha-input').keyup(function(e){

    if(e.keyCode == 8){
        return false;
    }
    var v = $(this).val();
    var vv = /^(\d{1,6}|(\d{2}\/\d{2}\/\d{4}))$/;
    //if(isNaN(v)){
    if(!vv.test(v) && v.length > 0){
        Modal.poner("Solo se adminten números<br>Ejemplo:150116 sería 15/01/2016","Error en la Fecha");
        return;
    }
    if(v.length == 6){
        switch (v.length){
            case 6:
                var v1 = v.substring(0,2);
                var v2 = v.substring(2,4);
                var v3 = v.substring(4);
                if(parseInt(v3) >= g_ano15 && parseInt(v3) <= 99 ){
                    v3 = String('19')+v3;
                }else{
                    v3 = String('20')+v3;
                }
                var vf = String(v1)+"/"+String(v2)+"/"+String(v3);
                $(this).val(vf);
                break;
            case 7:
                var vsp = v.split('/');
                //var v3 = v.substring(4);
                var vf = String(vsp[0])+String(vsp[1])+String(vsp[2]);
                $(this).val(vf);
                break;
            case 8:
                var v1 = v.substring(0,2);
                var v2 = v.substring(2,4);
                var v3 = v.substring(4);
                var vf = String(v1)+"/"+String(v2)+"/"+String(v3);
                $(this).val(vf);
                break;
        }


    }else{
        return false;
    }
})


//-----------------------------------

$(document).ready(function(){

    btnrand();
    //passrand();
    g_ancho = $(window).width();
    var disp = navigator.userAgent.toLowerCase();
    if(disp.search(/iphone|ipod|ipad|android|sailfish|mobile/) > -1){
        g_navega = 'movil';
        //$('#llamada').css('text-align','left');

    }else{
        g_navega = 'pc';
        //$('#llamada').css('text-align','right');
    }

});
