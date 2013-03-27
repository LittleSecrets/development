$(document).ready (function(){
    $('#tooltip').hide();

    zfMyAdmin.modules = {}
    

    /*Select module*/
    $( "#module-select-bar" ).dialog({
        autoOpen: false,
        open: function(event, ui) {  },
        modal: true,
        width: '50%'
    });  
    
    $("#module-select-items button" ).live('click',function(){
        $( "#module-select-label" ).text($(this).val());
        $('select#module-name').val($(this).val());
        $( "#module-select-bar" ).dialog( "close" );
        $('form.creator-form').trigger('change');
    })
    
    /*Tooltip functions start*/
    $("#controller-name").on('focus', function(){
        var moduleName = $('select#module-name').val();
        var url = "/zfmyadmin/data/controllers-list";
        var data = {'moduleName': moduleName, 'type': 'tooltip'};
        $.getJSON(url, data, function(response) {
            zfMyAdmin.modules[moduleName] = response;                
            $('#tooltip').html(zfMyAdmin.modules[moduleName].htmlBody);
            if (zfMyAdmin.modules[moduleName].number >= 1) {
                $('#tooltip').fadeIn(1000);
            };         
        });  
    });


    $("#controller-name").on('keyup', function(){
        $('span.tooltip-item').removeClass('red');
        var moduleName = $('select#module-name').val();
        var data = zfMyAdmin.modules[moduleName].controllers;
        var inputValue = $("#controller-name").val();
        var matchingElement = zfMyAdmin.findInputMatch(inputValue , data, true);
        if (matchingElement) {
            $('#tooltip-'+moduleName+'-'+matchingElement.name).addClass('red'); 
        }
    });
   

    $("#controller-name").on('blur', function() {
       $('#tooltip').fadeOut(1000);
    });
    
    /*Tooltip functions end*/
    
});