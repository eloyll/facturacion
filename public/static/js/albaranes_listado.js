$('.check-alba').click(function(){
    var id = $(this).prop('id');
    if($('input[id^="'+id+'"]').is(':checked')){
        $('input[id^="'+id+'"]').prop('checked',false);
        $('#'+id+'-check').css('display','none');
    }else{
        $('input[id^="'+id+'"]').prop('checked',true);
        $('#'+id+'-check').css('display','block');
    }
});

$('.itemalba').change(function(){
    var id = $(this).prop('id').split('#');
    if(!$(this).is(':checked')){
        $('#'+id[0]+'-check').css('display','none');
    }
});

$('#btn-pasarafactura').click(function(){
    var che = 'no';
    var siva = opener.$('input[name=exentoiva]:checked').val();
    $('input[type=checkbox]').each(function(){
        if($(this).is(':checked')){
            che = 'si';
        }
    });
    if(che == 'no'){
        opener.Modal.poner('Debes de seleccionar un item por lo menos','Albaranes','');
        return false;
    }



    $('input[type=checkbox]:checked').each(function () {
        var esta = 'no';
        var idalba = $(this).data('idalba');
        for(var i in opener.g_items){

            if(idalba == opener.g_items[i].idalba){
                esta = 'si';
            }
        }

        if(esta == 'no'){
            var valoriva = $(this).data('iva');
            if(siva == 'si'){
                valoriva = "0.00";
            }
            var txtcon = $('input[name=tetxofac]:checked').val();
            var concepto = $(this).data('concepto');
            if(txtcon == 'si'){
                concepto = $(this).data('concepto')+' ('+$(this).data('numero_alba')+')';
            }
            opener.g_items[opener.g_conitem] = {
                "cantidad": $(this).data('cantidad'),
                "codigo": $(this).data('codigo'),
                "concepto": concepto,
                "descuento": $(this).data('descuento'),
                "iva": valoriva,
                "precio": $(this).data('precio'),
                "importe": $(this).data('importe'),
                "cliente_cif":$(this).data('cliente_cif'),
                "numero_alba":$(this).data('numero_alba'),
                "idalba":$(this).data('idalba')
            };
            opener.g_conitem = opener.g_conitem + 1;
        }

    });
    opener.poneritems();
    console.log(opener.g_items)
    window.close();
});
