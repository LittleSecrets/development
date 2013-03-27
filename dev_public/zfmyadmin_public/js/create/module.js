$(function(){
    $('#tooltip').hide();
   /*Tooltip functions start*/
    
    $("#module-name").on('focus', function(){
        $('#tooltip').fadeIn(1000);
    });


    $("#module-name").on('keyup', function(){
        $('span.tooltip-item').removeClass('red');
        var inputValue = $("#module-name").val();
        var matchingElement = zfMyAdmin.findInputMatch(inputValue , zfMyAdmin.modules, false);
        if (matchingElement) {
            $('#tooltip-'+matchingElement).addClass('red'); 
        }
    });
   

    $("#module-name").on('blur', function() {
       $('#tooltip').fadeOut(1000);
    });
    
    /*Tooltip functions end*/

})