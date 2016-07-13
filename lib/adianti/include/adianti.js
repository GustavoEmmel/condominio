function Adianti(){}

/**
 * Goto a given page
 */
function __adianti_goto_page(page)
{
    window.location = page;
}

/**
 * Returns the URL Base
 */
function __adianti_base_url()
{
   return window.location.protocol +'//'+ window.location.host + window.location.pathname.split( '/' ).slice(0,-1).join('/');
}

/**
 * Returns the query string
 */
function __adianti_query_string()
{
    var query_string = {};
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i=0; i<vars.length; i++)
    {
        var pair = vars[i].split("=");
        if (typeof query_string[pair[0]] === "undefined")
        {
            query_string[pair[0]] = pair[1];
            // If second entry with this name
        }
        else if (typeof query_string[pair[0]] === "string")
        {
            var arr = [ query_string[pair[0]], pair[1] ];
            query_string[pair[0]] = arr;
        }
        else
        {
            query_string[pair[0]].push(pair[1]);
        }
    } 
    return query_string;
}

/**
 * Converts query string into json object
 */
function __adianti_query_to_json(query)
{
    var decode = function (s) { return decodeURIComponent(s.replace(/\+/g, " ")); };
    var urlParams = {};
    var search = /([^&=]+)=?([^&]*)/g;
    while (match = search.exec(query)) {
       urlParams[decode(match[1])] = decode(match[2]);
    }
    return urlParams;
}

/**
 * Loads an HTML content
 */
function __adianti_load_html(content, afterCallback)
{
    if ($('[widget="TWindow"]').length > 0 && (content.indexOf("TWindow") > 0))
    {
        $('[widget="TWindow"]').attr('remove', 'yes');
        $('#adianti_online_content').empty();
        content = content.replace(new RegExp('__adianti_append_page', 'g'), '__adianti_append_page2'); // chamadas presentes em botões seekbutton em window, abrem em outra janela
        $('#adianti_online_content').html(content);
        $('[widget="TWindow"][remove="yes"]').remove();
        $('#adianti_online_content').show();
    }
    else
    {
        if (content.indexOf("TWindow") > 0)
        {
            content = content.replace(new RegExp('__adianti_append_page', 'g'), '__adianti_append_page2'); // chamadas presentes em botões seekbutton em window, abrem em outra janela
            $('#adianti_online_content').html(content);
        }
        else
        {
            $('[widget="TWindow"]').remove();
            $('#adianti_div_content').html(content);
        }
    }
    
    if (typeof afterCallback == "function")
    {
        afterCallback();
    }
}

/**
 * Loads an HTML content. This function is called if there is an window opened.
 */
function __adianti_load_html2(content)
{
   if ($('[widget="TWindow2"]').length > 0)
   {
       $('[widget="TWindow2"]').attr('remove', 'yes');
       $('#adianti_online_content2').hide();
       content = content.replace(new RegExp('__adianti_load_html', 'g'), '__adianti_load_html2'); // se tem um botão de buscar, ele está conectado a __adianti_load_html
       content = content.replace(new RegExp('__adianti_post_data', 'g'), '__adianti_post_data2'); // se tem um botão de buscar, ele está conectado a __adianti_load_html
       content = content.replace(new RegExp('TWindow','g'), 'TWindow2'); // quando submeto botão de busca, é destruído tudo que tem TWindow2 e recarregado
       content = content.replace(new RegExp('generator="adianti"', 'g'), 'generator="adianti2"'); // links também são alterados
       $('#adianti_online_content2').html(content);
       $('[widget="TWindow2"][remove="yes"]').remove();
       $('#adianti_online_content2').show();
   }
   else
   {
       if (content.indexOf("TWindow2") > 0)
       {
           $('#adianti_online_content2').html(content);
       }
       else if (content.indexOf("TWindow") > 0)
       {
           $('#adianti_online_content').html(content);
       }
       else
       {
           $('#adianti_div_content').html(content);
       }
   }
}

function __adianti_load_page_no_register(page)
{
    $.get(page, function(data)
    {
        __adianti_load_html(data);
    });
}

function __adianti_load_page_no_register2(page)
{
    $.get(page, function(data)
    {
        __adianti_load_html2(data);
    });
}

/**
 * Called by Seekbutton. Add the page content. 
 */
function __adianti_append_page(page)
{
    page = page.replace('engine.php?','');
    params_json = __adianti_query_to_json(page);

    uri = 'engine.php?' 
        + 'class=' + params_json.class
        + '&method=' + params_json.method
        + '&static=' + (params_json.static == '1' ? '1' : '0');

    $.post(uri, params_json, function(data)
    {
        data = data.replace(new RegExp('__adianti_append_page', 'g'), '__adianti_append_page2'); // chamadas presentes em botões seekbutton em window, abrem em outra janela
        $('#adianti_online_content').after('<div></div>').html(data);
    });
}

/**
 * Called by Seekbutton from opened windows. 
 */
function __adianti_append_page2(page)
{
    page = page.replace('engine.php?','');
    params_json = __adianti_query_to_json(page);

    uri = 'engine.php?' 
        + 'class=' + params_json.class
        + '&method=' + params_json.method
        + '&static=' + (params_json.static == '1' ? '1' : '0');

    $.post(uri, params_json, function(data)
    {
        data = data.replace(new RegExp('__adianti_load_html', 'g'), '__adianti_load_html2'); // se tem um botão de buscar, ele está conectado a __adianti_load_html
        data = data.replace(new RegExp('__adianti_post_data', 'g'), '__adianti_post_data2'); // se tem um botão de buscar, ele está conectado a __adianti_load_html
        data = data.replace(new RegExp('TWindow', 'g'),             'TWindow2'); // quando submeto botão de busca, é destruído tudo que tem TWindow2 e recarregado
        data = data.replace(new RegExp('generator="adianti"', 'g'), 'generator="adianti2"'); // links também são alterados
        $('#adianti_online_content2').after('<div></div>').html(data);
    });
}

/**
 * Open a page using ajax
 */
function __adianti_load_page(page)
{
    if (typeof page !== 'undefined')
    {
        $( '.modal-backdrop' ).remove();
        
        url = page;
        url = url.replace('index.php', 'engine.php');
        
        if (typeof Adianti.onBeforeLoad == "function")
        {
            Adianti.onBeforeLoad(url);
        }
        
        if (url.indexOf('&static=1') > 0)
        {
            $.get(url, function(data)
            {
                __adianti_parse_html(data);
                
                if (typeof Adianti.onAfterLoad == "function")
                {
                    Adianti.onAfterLoad();
                }
            });
        }
        else
        {
            $.get(url, function(data)
            {
                __adianti_load_html(data, Adianti.onAfterLoad);
                
                if ( history.pushState && (data.indexOf("TWindow") < 0) )
                {
                    __adianti_register_state(url, 'adianti');
                }
            });
        }
    }
}

/**
 * Used by all links inside a window (generator=adianti)
 */
function __adianti_load_page2(page)
{
    url = page;
    url = url.replace('index.php', 'engine.php');
    __adianti_load_page_no_register2(url);
}

/**
 * Start blockUI dialog
 */
function __adianti_block_ui(wait_message)
{
    if (typeof Adianti.blockUIConter == 'undefined')
    {
        Adianti.blockUIConter = 0;
    }
    Adianti.blockUIConter = Adianti.blockUIConter + 1;
    if (typeof wait_message == 'undefined')
    {
        wait_message = Adianti.waitMessage;
    }
    
    $.blockUI({ 
       message: '<h1><i class="fa fa-spinner fa-spin"></i> '+wait_message+'</h1>',
       css: { 
           border: 'none', 
           padding: '15px', 
           backgroundColor: '#000', 
           'border-radius': '5px 5px 5px 5px',
           opacity: .5, 
           color: '#fff' 
       }
    });
}

/**
 * Show message dialog
 */
function __adianti_message(title, message, callback)
{
    bootbox.dialog({
      title: title,
      message: '<div>'+
                '<span class="fa fa-fa fa-info-circle fa-5x blue" style="float:left"></span>'+
                '<span display="block" style="margin-left:20px;float:left">'+message+'</span>'+
                '</div>',
      buttons: {
        success: {
          label: "OK",
          className: "btn-default",
          callback: function() {
            this.close();
            if (typeof callback != 'undefined')
            { 
                callback();
            }
          }
        }
      }
    });
}

/**
 * Show question dialog
 */
function __adianti_question(title, message, callback)
{
    bootbox.dialog({
      title: title,
      message: '<div>'+
                '<span class="fa fa-fa fa-question-circle fa-5x blue" style="float:left"></span>'+
                '<span display="block" style="margin-left:20px;float:left">'+message+'</span>'+
                '</div>',
      buttons: {
        ok: {
          label: "OK",
          className: "btn-default",
          callback: function() {
            this.close();
            if (typeof callback != 'undefined')
            { 
                callback();
            }
          }
        },
        cancel: {
          label: "Cancel",
          className: "btn-default",
          callback: function() {
            this.close();
          }
        }
      }
    });
}

/**
 * Closes blockUI dialog
 */
function __adianti_unblock_ui()
{
    Adianti.blockUIConter = Adianti.blockUIConter -1;
    if (Adianti.blockUIConter <= 0)
    {
        $.unblockUI();
        Adianti.blockUIConter = 0;
    }
}

/**
 * Post form data
 */
function __adianti_post_data(form, action)
{
    __adianti_block_ui();
    
    url = 'index.php?'+action;
    url = url.replace('index.php', 'engine.php');
    data = $('#'+form).serialize();
    
    if (typeof Adianti.onBeforePost == "function")
    {
        Adianti.onBeforePost(url);
    }
    
    if (url.indexOf('&static=1') > 0)
    {
        $.post(url, data,
            function(result) {
                __adianti_parse_html(result);
                __adianti_unblock_ui();
                
                if (typeof Adianti.onAfterPost == "function")
                {
                    Adianti.onAfterPost();
                }
            });
    }
    else
    {
        $.post(url, data,
            function(result) {
                __adianti_load_html(result, Adianti.onAfterPost);
                __adianti_unblock_ui();
            });
    }
}

/**
 * Post form data over window
 */
function __adianti_post_data2(form, url)
{
    url = 'index.php?'+url;
    url = url.replace('index.php', 'engine.php');
    data = $('#'+form).serialize();
    
    $.post(url, data,
        function(result)
        {
            __adianti_load_html2(result);
            __adianti_unblock_ui();
        });
}

/**
 * Register URL state
 */
function __adianti_register_state(url, origin)
{
    if (Adianti.registerState !== false || origin == 'user')
    {
        var stateObj = { url: url };
        if (typeof history.pushState != 'undefined')
        {
            history.pushState(stateObj, "", url.replace('engine.php', 'index.php'));
        }
    }
}

/**
 * Ajax lookup
 */
function __adianti_ajax_lookup(action, field)
{
    var value = field.value;
    __adianti_ajax_exec(action +'&key='+value+'&ajax_lookup=1', null, false);
}

/**
 * Execute an Ajax action
 */
function __adianti_ajax_exec(action, callback, async)
{
    async = typeof async !== 'undefined' ? async : true;
    uri = 'engine.php?' + action +'&static=1';
    
    $.ajax({
      url: uri,
      async: async }).done(function( data ) {
          __adianti_parse_html(data, callback);
      }).fail(function(jqxhr, settings, exception) {
         //alert(exception + ': ' + jqxhr.responseText);
         $('<div />').html(jqxhr.responseText).dialog({modal: true, title: 'Error', width : '80%', height : 'auto', resizable: true, closeOnEscape:true, focus:true});
      });
}

function __adianti_post_lookup(form, action, field) {
    var formdata = $('#'+form).serializeArray();
    var uri = 'engine.php?' + action +'&static=1';
    formdata.push({name: 'key', value: field.value});
    formdata.push({name: 'ajax_lookup', value: 1});
    
    $.ajax({
      type: 'POST',
      url: uri,
      data: formdata,
      async: false }).done(function( data ) {
          __adianti_parse_html(data, null);
      }).fail(function(jqxhr, settings, exception) {
         //alert(exception + ': ' + jqxhr.responseText);
         $('<div />').html(jqxhr.responseText).dialog({modal: true, title: 'Error', width : '80%', height : 'auto', resizable: true, closeOnEscape:true, focus:true});
      });
}

/**
 * Parse returning HTML
 */
function __adianti_parse_html(data, callback)
{
    tmp = data;
    tmp = new String(tmp.replace(/window\.opener\./g, ''));
    tmp = new String(tmp.replace(/window\.close\(\)\;/g, ''));
    tmp = new String(tmp.replace(/(\n\r|\n|\r)/gm,''));
    tmp = new String(tmp.replace(/^\s+|\s+$/g,""));
    if ($('[widget="TWindow2"]').length > 0)
    {
       // o código dinâmico gerado em ajax lookups (ex: seekbutton)
       // deve ser modificado se estiver dentro de window para pegar window2
       tmp = new String(tmp.replace(/TWindow/g, 'TWindow2'));
    }
    
    try {
        
        $('#adianti_online_content').append(tmp);
        
        if (callback && typeof(callback) === "function")
        {
            callback(data);
        }
    } catch (e) {
        if (e instanceof Error) {
            //alert(e.message + ': ' + tmp);
            $('<div />').html(e.message + ': ' + tmp).dialog({modal: true, title: 'Error', width : '80%', height : 'auto', resizable: true, closeOnEscape:true, focus:true});
        }
    }
}

/**
 * Download a file
 */
function __adianti_download_file(file)
{
    extension = file.split('.').pop();
    screenWidth  = screen.width;
    screenHeight = screen.height;
    if (extension !== 'html')
    {
        screenWidth /= 3;
        screenHeight /= 3;
    }
    
    window.open('download.php?file='+file, '_blank',
      'width='+screenWidth+
     ',height='+screenHeight+
     ',top=0,left=0,status=yes,scrollbars=yes,toolbar=yes,resizable=yes,maximized=yes,menubar=yes,location=yes');
}

/**
 * Process popovers
 */
function __adianti_process_popover()
{
    $('[popover="true"]').popover({
        placement: 'auto top',
        trigger: 'hover',
        container: 'body',
        template: '<div class="popover" role="tooltip" style="z-index:100000;max-width:800px"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"><div class="data-content"></div></div></div>',
        delay: {
            show: 10,
            hide: 10
        },
        content: function () {
            return $(this).attr("popcontent");
        },
        html: true,
        title: function () {
            return $(this).attr("poptitle");
        }
    });
    
    $('body').on('click', function (e) {
        $('.tooltip').hide();
        if ($(e.target).data('toggle') !== 'popover')
        {
            $('.popover').popover('toggle');
        }
    });
}

/**
 * Start actions
 */
$(function() {
    Adianti.blockUIConter = 0;
    $(document.body).tooltip({
        selector: "[title]",
        placement: 'right',
        trigger: 'hover',
        cssClass: 'tooltip',
        container: 'body',
        content: function () {
            return $(this).attr("title");
        },
        html: true
    });
    
    $( document ).on( "dialogopen", function(){
        __adianti_process_popover();
    });
    
    $.ui.dialog.prototype._focusTabbable = $.noop;
});

/**
 * On Ajax complete actions
 */
$(document).ajaxComplete(function ()
{
    __adianti_process_popover();
    
    $('table[datatable="true"]:not(.dataTable)').DataTable( {
        responsive: true,
        paging: false,
        searching: false,
        ordering:  false,
        info: false
    } );
});

/**
 * Override the default page loader
 */
$( document ).on( 'click', '[generator="adianti"]', function()
{
   __adianti_load_page($(this).attr('href'));
   return false;
});

/**
 * Override the default page loader for new windows
 */
$( document ).on( 'click', '[generator="adianti2"]', function()
{
   __adianti_load_page2($(this).attr('href'));
   return false;
});

/**
 * Register page navigation
 */
window.onpopstate = function(stackstate)
{
    if (stackstate.state)
    {
        __adianti_load_page_no_register(stackstate.state.url);
    }
};