$(document).ready (function(){
    $('#tooltip').hide();
    zfMyAdmin.modules = {}
    zfMyAdmin.tooltip = {}

    /*Select module/controller functions start*/
    $( "#module-select-bar" ).dialog({
        autoOpen: false,
        open: function(event, ui) {  },
        modal: true,
        width: '50%'
    });  

    $( "#controller-select-bar" ).dialog({
        autoOpen: false,
        open: function(event, ui) {  },
        modal: true,
        width: '50%'
    }); 
    
    $("#module-select-items button" ).bind('click',function(){
        var moduleName = $(this).val();
        $( "#module-select-label" ).text(moduleName);
        $('select#module-name').val(moduleName);
        $( "#module-select-bar" ).dialog( "close" );
       
       /*Get new controller lisr*/
        var url = "/zfmyadmin/data/controllers-list";
        var data = {'moduleName': moduleName, 'type': 'form'};
        $('#controller-select-items').html('');
        $('#controller-name-hidden-form').html('');
        $('#controller-select-label' ).html('');
        $( "#controller-select-button" ).addClass('ui-state-disabled');
        
        $.getJSON(url, data, function(response) {
            zfMyAdmin.modules[moduleName] = response;                
            $('#controller-select-items').html(zfMyAdmin.modules[moduleName].htmlBody);
            $('#controller-name-hidden-form').html(zfMyAdmin.modules[moduleName].htmlBodyHiddenForm);
            if(response.number > 0) {
                $('#controller-select-label' ).text($("select#controller-name").val()+'Controller.php');
                $('#controller-select-button' ).removeClass('ui-state-disabled');
            }
            $('form.creator-form').trigger('change');
        }); 
      
    })

    $( "#controller-select-button" ).bind('click', function(){
        if(!$(this).hasClass('ui-state-disabled')){
            $( "#controller-select-bar" ).dialog( "open" );
        }        
    });

    $("#controller-select-items button" ).live('click',function(){        
        $( "#controller-select-label" ).text($(this).val());
        var name = $(this).attr('data-name');
        $("select#controller-name").val(name);
        $( "#controller-select-bar" ).dialog( "close" );
        $('form.creator-form').trigger('change');
    })

    $("#controller-name").on('blur', function() {
        $('#tooltip').fadeOut(1000);
    });
    
    /*Tooltip functions start*/
    $("#action-name").on('focus', function(){
        var moduleName = $("select#module-name").val();
        var controllerName = $("select#controller-name").val();
        var url = "/zfmyadmin/data/actions-list";
        var data = {'moduleName': moduleName, 'controllerName':controllerName, 'type': 'tooltip'};
        $.getJSON(url, data, function(response) {               
            $('#tooltip').html(response.htmlBody);
            if (zfMyAdmin.tooltip[response.moduleName] == undefined) {
                zfMyAdmin.tooltip[response.moduleName] = {}
            }
            if (zfMyAdmin.tooltip[response.moduleName][response.controllerName] == undefined) {
                zfMyAdmin.tooltip[response.moduleName][response.controllerName] = {}
            }            
            zfMyAdmin.tooltip[response.moduleName][response.controllerName] = response.actions;
            if (response.number >= 1) {
                $('#tooltip').fadeIn(1000);
            }; 
        });  
    });
    
    $("#action-name").on('keyup', function(){
        $('span.tooltip-item').removeClass('red');
        var moduleName = $('select#module-name').val();
        var controllerName = $('select#controller-name').val();        
        var data = zfMyAdmin.tooltip[moduleName][controllerName];
        var inputValue = $("#action-name").val();
        var matchingElement = zfMyAdmin.findInputMatch(inputValue , data, false);
        if (matchingElement) {
            $('#tooltip-'+moduleName+'-'+controllerName+'-'+matchingElement).addClass('red'); 
        }
    });
    
    $("#action-name").on('blur', function() {
       $('#tooltip').fadeOut(1000);
    });
   
   
}); 