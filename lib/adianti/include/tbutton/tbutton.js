function tbutton_enable_field(form_name, field) {
    setTimeout(function(){ $('form[name='+form_name+'] [name='+field+']').removeAttr('disabled') },1);    
}

function tbutton_disable_field(form_name, field) {
    setTimeout(function(){ $('form[name='+form_name+'] [name='+field+']').attr('disabled', true) },1);    
}