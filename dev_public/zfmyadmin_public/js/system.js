var zfMyAdmin = {};
zfMyAdmin.populateDataToForm = function(data, form){
    $.each(data, function(index, value) {
        if(typeof(value)== 'object') {
            $.each(value, function(index2, value2) {                
               var element = form.find('[name="'+index+'['+index2+']"]');               
               if ((element.prop('type')== 'text')||(element.prop('type')== 'select-one')) {
                    element.val(value2); 
               }
               element.prop("checked", value2-0);               
            })
            
        } else {
           var element = form.find('[name="'+index+'"]');            
           if ((element.prop('type')== 'text')||(element.prop('type')== 'select-one')) {
                element.val(value); 
           }
           element.prop("checked", value-0);
           
        }

    });    
}

zfMyAdmin.selectModuleControllerAction = function(){
    zfMyAdmin.modules = {}
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
    
    $( "#action-select-bar" ).dialog({
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
        
        $('#action-select-items').html('');
        $('#action-name-hidden-form').html('');
        $('#action-select-label' ).html('');
        $("#action-select-button" ).addClass('ui-state-disabled');
        
        $.getJSON(url, data, function(response) {
            zfMyAdmin.modules[moduleName] = response;
            $('#controller-select-items').html(zfMyAdmin.modules[moduleName].htmlBody);
            $('#controller-name-hidden-form').html(zfMyAdmin.modules[moduleName].htmlBodyHiddenForm);
            if(response.number > 0) {
                $('#controller-select-label' ).text($("select#controller-name").val()+'Controller.php');
                $('#controller-select-button' ).removeClass('ui-state-disabled');
            }            
            /*Get and new actions lisr*/
            var url = "/zfmyadmin/data/actions-list";            
            var controllerName = $('select#controller-name').val();
            var moduleName = $('select#module-name').val();
            var data = {'moduleName': moduleName, 'controllerName':controllerName, 'type': 'form-css'};
            $.getJSON(url, data, function(response) {               
                $('#action-select-items').html(response.htmlBody);
                $('#action-name-hidden-form').html(response.htmlBodyHiddenForm);
                if(response.number > 0) {
                    $('#action-select-label' ).text($("select#action-name").val()+'Action()');
                    $('#action-select-button' ).removeClass('ui-state-disabled');
                }
                $('form.creator-form').trigger('change');
            });           
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
        
        var controllerName = $('select#controller-name').val();
        var moduleName = $('select#module-name').val();
       /*Get new actions lisr*/
        var url = "/zfmyadmin/data/actions-list";
        var data = {'moduleName': moduleName, 'controllerName':controllerName, 'type': 'form-css'};
        $('#action-select-items').html('');
        $('#action-name-hidden-form').html('');
        $('#action-select-label' ).html('');
        $("#action-select-button" ).addClass('ui-state-disabled');        
        $.getJSON(url, data, function(response) {               
            $('#action-select-items').html(response.htmlBody);
            $('#action-name-hidden-form').html(response.htmlBodyHiddenForm);
            if(response.number > 0) {
                $('#action-select-label' ).text($("select#action-name").val()+'Action()');
                $('#action-select-button' ).removeClass('ui-state-disabled');
            }
            $('form.creator-form').trigger('change');
        });         
        
        $( "#controller-select-bar" ).dialog( "close" );
        $('form.creator-form').trigger('change');
    })

    $( "#action-select-button" ).bind('click', function(){
        if(!$(this).hasClass('ui-state-disabled')){
            $( "#action-select-bar" ).dialog( "open" );
        }        
    });

    $("#action-select-items button" ).live('click',function(){        
        $( "#action-select-label" ).text($(this).val());
        var name = $(this).attr('data-name');
        $("select#action-name").val(name);
        $( "#action-select-bar" ).dialog( "close" );
        $('form.creator-form').trigger('change');
    })

   
}