function tmultisearch_start( id, minlen, maxsize, placeholder, multiple, preload_items, width, height, load_data, callback ) {
    $('#'+id).select2( {
        minimumInputLength: minlen,
        maximumSelectionSize: maxsize,
        allowClear: true,
        separator: '||',
        placeholder: placeholder,
        multiple: multiple,
        id: function(e) { return e.id + "::" + e.text; },
        query: function (query)
        {
            var data = {results: []};
            preload_data = preload_items;
            $.each(preload_data, function() {
                if(query.term.length == 0 || this.text.toUpperCase().indexOf(query.term.toUpperCase()) >= 0 ){
                    data.results.push({id: this.id, text: this.text });
                }
            });
            query.callback(data);
        }
    });
    
    if (typeof callback != 'undefined')
    {
        $('#'+id).on("change", function (e) {
            callback();
        });
    }
    
    $('#s2id_'+id+ '> .select2-choices').height(height).width(width).css('overflow-y','auto');
    
    if (typeof load_data !== "undefined") {
        $('#'+id).select2("data", load_data);
    }
}

function tmultisearch_get_form_data(formName, fieldName) {
    element = $('input[name='+fieldName+'][component="multisearch"]')[0];
    return $('#'+formName+' :input[name!="'+fieldName+'"]').serialize() + '&' + $.param(tmultisearch_get_value(element));
}

function tmultisearch_get_value(element) {
    var fieldName = element.name;
    var select_ids = [];
    rows = element.value.split('||');
    $(rows).each(function(i) {
        item = this.split('::');
        select_ids.push( item[0] );
    });
    data = new Object;
    data[fieldName] = select_ids;
    return data;
}

function tmultisearch_enable_field(form_name, field) {
    try { $('#s2id_'+$('form[name='+form_name+'] [name="'+field+'"]').attr('id')).select2("enable", true); } catch (e) { }    
}

function tmultisearch_disable_field(form_name, field) {
    try { $('#s2id_'+$('form[name='+form_name+'] [name="'+field+'"]').attr('id')).select2("enable", false); } catch (e) { }    
}