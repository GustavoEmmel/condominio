function tdate_enable_field(form_name, field) {
    try{ $('form[name='+form_name+'] [name='+field+']').attr('disabled', false); } catch (e) { }
    try{ $('form[name='+form_name+'] [name='+field+']').removeClass('tfield_disabled').addClass('tfield'); } catch (e) { }
    setTimeout(function(){ $('form[name='+form_name+'] [name='+field+']').next().show() },1);
} 
                            
function tdate_disable_field(form_name, field) {
    try{ $('form[name='+form_name+'] [name='+field+']').attr('disabled', true); } catch (e) { }
    try{ $('form[name='+form_name+'] [name='+field+']').removeClass('tfield_disabled').addClass('tfield'); } catch (e) { }
    setTimeout(function(){ $('form[name='+form_name+'] [name='+field+']').next().hide() },1);    
}

function tdate_start( id, mask, language) {
    $( id ).wrap( '<div class="tdate-group date">' );
    $( id ).after( '<span class="btn btn-default tdate-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>' );
    
    $( id ).closest('.tdate-group').datepicker({
        format: mask,
        todayBtn: "linked",
        language: language,
        calendarWeeks: false,
        autoclose: true,
        todayHighlight: true
    }).on('changeDate', function(e){
        if ( $( id ).attr('exitaction')) {
            eval( $ ( id ).attr('exitaction'));
        }
    });
}