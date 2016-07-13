function tcolor_enable_field(form_name, field) {
    try { setTimeout(function(){ $('form[name='+form_name+'] [name='+field+']').closest('.color-div').colorpicker('enable'); },1); } catch (e) { }    
}

function tcolor_disable_field(form_name, field) {
    try { setTimeout(function(){ $('form[name='+form_name+'] [name='+field+']').closest('.color-div').colorpicker('disable'); },1); } catch (e) { }
}

function tcolor_start() {
    $(function() {
        $('.color-div').colorpicker();
    });
}