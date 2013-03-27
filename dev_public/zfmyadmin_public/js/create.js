$(function() {  
    
    /*Logs and intention tabs*/
    var actionInfoTabs = $('#action-info-tabs');
    actionInfoTabs.tabs();
    /*Show logs tab after acton save*/
    if(zfMyAdmin.showLogTab) {
        actionInfoTabs.tabs( "select" , 'action-info-log');
    } else {
        actionInfoTabs.tabs( "select" , 'action-info-intention')
    }
    
    /*Hover for select module controller buttons*/
    $('#module-select-button, #controller-select-button').hover(
       function() {$(this).addClass('ui-state-hover');$(this).removeClass('ui-state-active');},
       function() {$(this).removeClass('ui-state-hover');$(this).addClass('ui-state-active');}
    );
    
    /*Hover for 16px buttons*/        
    $('.button-16').hover(
       function() {$(this).addClass('ui-state-hover');},
       function() {$(this).removeClass('ui-state-hover');}
    );
    
    /*Select module dialog open*/
    $( "#module-select-button" ).live('click', function(){
        $( "#module-select-bar" ).dialog( "open" );
    });
    
    /*Tabs of settings of creator*/
    $( "div.set-settings-block" ).tabs();
    
    
    /*save last user selection in creator form*/
    $('form.creator-form').bind('change', function(){
        var submit = $(this).find('#create-submit')
        submit.val(submit.attr('data-alternative-label'))
        $('#creator-settings-switcher a').removeClass('active');
        var url = "/zfmyadmin/user/settings-creator-form"
        var data = $(this).serialize()+'&settingsType=last_settings_type'; 
        $.post(url,data, function($response){

        })
    })
    
    /*set saved type of settings in creator form*/
    $('#creator-settings-switcher a').click(function(){
        $('#creator-settings-switcher a').removeClass('active');
        $(this).addClass('active');        
        var settings = zfMyAdmin.creatorSettings[$(this).attr('data-type')];
        if(settings) {
            var form = $('form.creator-form');
            $( "#module-select-label" ).text(settings.moduleName);
            if ($("select#controller-name").length > 0) {
                var url = "/zfmyadmin/data/controllers-list";
                var data = {'moduleName': settings.moduleName, 'type': 'form'};
                $.getJSON(url, data, function(response) {              
                    $('#controller-select-items').html(response.htmlBody);
                    $('#controller-name-hidden-form').html(response.htmlBodyHiddenForm);                    
                    zfMyAdmin.populateDataToForm(settings, form);
                    $( "#controller-select-label" ).text($("select#controller-name").val()+'Controller.php');
                    $('form.creator-form').trigger('change');
                }); 
            } else {
               zfMyAdmin.populateDataToForm(settings, form);   
            }
                      
        }

    })
    
    /*save saved type of settings in creator form*/
    $('button.controller-save-settings').click(function(){
        var url = "/zfmyadmin/user/settings-creator-form"
        var dataType = $(this).attr('data-type');
        var data = $('form.creator-form').serialize()+'&settingsType='+dataType; 
        $.post(url,data, function(response){
            zfMyAdmin.creatorSettings[dataType] = response.newSettings;
            $('#creator-settings-switcher a').removeClass('active');
            $('#creator-settings-switcher a[data-type="'+dataType+'"]').addClass('active');
        })
    })
    
    /*prepares input text and checks if element exists, used for tooltips*/    
    zfMyAdmin.findInputMatch = function(inputValue , data, firstUpperCase) {
        inputValue = jQuery.trim(inputValue);
        inputValue = inputValue.replace(/(\W+([A-Za-z0-9]{1}))/g, function(g0,g1,g2) {return g2.toUpperCase()});
        inputValue = inputValue.replace(/(\_+([A-Za-z0-9]{1}))/g, function(g0,g1,g2) {return g2.toUpperCase()});
        if (firstUpperCase) {
            inputValue = inputValue.replace(/^[A-Za-z]{1}/, inputValue.slice(0,1).toUpperCase());          
        }
        if (data[inputValue] !== undefined) {           
            return data[inputValue]
        }
        return false;       
    }
    
    
    /*  used only form-creator for show demo cod of forms todo replace*/
    $('.read-code-button').live('click', function() {
        $('#read-code-window').dialog( "open" );
    });
    
    /*this function adds a row with variables*/
    $('#router-add-var-button').live('click', function() {  
        var lastIndex = parseInt($('#create-router-vars tbody tr:last').attr('data-number'));
        var newIndex = lastIndex + 1
        var lastVarRow = $('tr[data-number="'+lastIndex+'"]'); 
        var newVarRow = lastVarRow.clone(true).insertAfter(lastVarRow);

        newVarRow.attr('data-number', newIndex);

        var newVarRowName = newVarRow.find('td.router-var-name input');           
        newVarRowName.attr('name', "router[routerVars_"+newIndex+"][Name]");
        newVarRowName.attr('id', "router-routerVars_"+newIndex+"-Name");
        newVarRowName.val('');

        var newVarRowValue = newVarRow.find('td.router-var-value input');           
        newVarRowValue.attr('name', "router[routerVars_"+newIndex+"][Value]");
        newVarRowValue.attr('id', "router-routerVars_"+newIndex+"-Value");
        newVarRowValue.val('');

        var newVarRowButton = newVarRow.find('td div.router-var-button-delete');
        newVarRowButton.attr('data-number', newIndex);

        return false;
    });

    /*this function deletes a row with variables*/
    $('#create-router-vars .router-var-button-delete').live('click', function() {  
        var dN = $(this).attr('data-number');
        $('tr[data-number="'+dN+'"]').remove();
    });

       

});
