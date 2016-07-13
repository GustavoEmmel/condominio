function tcheckgroup_enable_field(form_name, field) {
    setTimeout(function(){ $('form[name='+form_name+'] [checkgroup='+field+']').removeAttr('disabled') },1);    
}

function tcheckgroup_disable_field(form_name, field) {
    setTimeout(function(){ $('form[name='+form_name+'] [checkgroup='+field+']').attr('disabled', '') },1);    
}

function tcheckgroup_clear_field(form_name, field) {
    setTimeout(function(){ $('form[name='+form_name+'] [checkgroup='+field+']').attr('checked', false) },1);    
}