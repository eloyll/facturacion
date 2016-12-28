var g_ancho = '';
g_idmovermodal = '';
var g_foco = '';
var g_navega = '';
var g_titulomodal = '';
var g_items = {};
var g_vctmo = {};
var g_nvcmo = 0;
var g_iva = {};
var g_bi_iva = {};
var g_ret = 0;
var g_req = 0;
var g_total = 0;
var g_conitem = 0;
var g_totitem = 0;
var g_moditem = 'no';
var g_boritem = 'no';
var g_keycode = 0;
var g_dato_input = "";
var g_id_input = "";
var g_fecha = new Date();
var g_dia = String(g_fecha.getDate());
if(g_dia.length == 1){
    g_dia = "0"+g_dia;
}
var g_mes = String(g_fecha.getMonth()+1);
if(g_mes.length == 1){
    g_mes = "0"+g_mes;
}
var g_ano = g_fecha.getFullYear();
var g_ano15 = String(g_fecha.getFullYear()+15).substring(2);
var g_hoy = String(g_dia)+"/"+String(g_mes)+"/"+String(g_ano);

$.fn.errorForm = function(){
    var ide = this.prop('id');
    var col = $("#"+ide).css("color");
    var bcol = $("#"+ide).css("background-color");
    $("#"+ide).css({"background-color":"rgba(153,0,0,0.2)","color":"#000"});
    //Moverto.idelem(ide);
    g_idmovermodal = ide; // cuando se cierre la modal el scroll se pone en el elemento
    //Modal.poner('Este campo contiene errores<br>O no puede estar vacío','Validación formulario',ide);
    this.focus(function(){
        $("#"+ide).css({"background-color":bcol,"color":col})
    });

    /* setTimeout(
     function(){
     $("#"+ide).css("background-color","#FFF");
     },3000);*/
};
Moverto = {
    idelem: function(id) {
        if (id.length > 0){
            var scrl = parseInt($("#" + id).offset().top) - 70;
            $('html,body').animate({scrollTop: scrl}, 'slow');
        }

    }
};

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

            if(g_idmovermodal != undefined){
                Moverto.idelem(g_idmovermodal);
            }

            g_idmovermodal = '';
        });
        if(g_foco != undefined){
            $('#'+g_foco).focus().select();
        }
        g_foco = '';

    }
};

$('#bt-cerrar-modal').click(function(){
    Modal.sacar();
});
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
});

//Pone la primera letra letra de las palabras en MAYUSCULAS----------------

function priMay(tx){
    if(tx == undefined){
        return;
    }
    var txtspl = tx.toLowerCase().split(' ');
    var texto = '';
    for (var i=0;i < txtspl.length;i++){
        var splw = txtspl[i].split('');
        splw[0] = splw[0].toUpperCase();
        for (var j = 0;j < splw.length;j++){
            texto += splw[j];
        }
        if(i < txtspl.length-1){
            texto += ' ';
        }
    }
    return texto;
}

//Control de los decimales en funcio de la variable global

$('.decimals').blur(function(){
    var da = "";
    var id = "";
    if(g_keycode == 13){
        da = g_dato_input;
        id = g_id_input;
        g_id_input = '';
        g_dato_input ='';
        g_keycode = 0;
    }else{
        da = $(this).val();
        id = $(this).prop('id');
    }
    if(da.length < 1){
        da = (0/1).toFixed(g_decimales);
        $('#'+id).val(da);
        return;
    }

    var valor = '';
    var patron = /^(\-)?[0-9]+([\.]?[0-9]{0,})$/;
    if(!patron.test(da)){
        Modal.poner("Solo se adminten números y el signo 'menos'<br>Ejemplo: 150116.255 ó -2236.36","Error de formato");
        setTimeout(function(){
            $('#'+id).focus().select();
        },100);
        return false;
    }else{
        valor = (da/1).toFixed(g_decimales);
        $('#'+id).val(valor);
        if(id == 'precio'){
            $('#btn-item').trigger('click');
        }
    }
});

$('.decimal2').blur(function(){
    var da = $(this).val();
    var id = $(this).prop('id');
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
    }
});


//cierra colapsa los items del menu cuando estan reducidos

/*$(function(){
    var navMain = $("#nav-main");
    navMain.on("click", ".cierra", null, function () {
        navMain.collapse('hide');
    });
});*/



















