$(function(){
    zfMyAdmin.selectModuleControllerAction(); 
    
    $( "#add-form-field-dialog" ).dialog({
        autoOpen: false,
        open: function(event, ui) {  },
        modal: true,
        width: '50%'
    }); 
    
    $( "#form-add-field-button" ).bind('click', function(){
            $('#set-form-field-settings-block').tabs( "select" , 'set-form-field-general');
            $( "#add-form-field-dialog" ).dialog( "open" );  
            
    });
});

