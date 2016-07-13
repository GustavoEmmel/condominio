function tsortlist_start( id, connect, change_action ) {
    $(function() {
        $( id ).sortable({
            connectWith: connect,
            start: function(event,ui){
                ui.item.data('index',ui.item.index());
                ui.item.data('parenta',this.id);
            },
            receive: function(event, ui) {
                var sourceList = ui.sender;
                var targetList = $(this);
                targetListName = this.getAttribute('itemname');
                document.getElementById(ui.item.attr('id') + '_input').name = targetListName + '[]';
            },
            deactivate: change_action,
        }).disableSelection();
    });
}

function tsortlist_enable_field(form_name, field) {
    setTimeout(function(){ $('form[name='+form_name+'] [itemname='+field+']').sortable('enable') },1);
}

function tsortlist_disable_field(form_name, field) {
    setTimeout(function(){ $('form[name='+form_name+'] [itemname='+field+']').sortable('disable') },1);    
}

function tsortlist_clear_field(form_name, field) {
    setTimeout(function(){ $('form[name='+form_name+'] [itemname='+field+']').empty( ) },1);    
}