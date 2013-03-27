$(function() {
    /*Loading list of operations*/
    $('.log-transaction-button').on('click', function(){
        var transactionId = $(this).attr('data-id');
        var container = $('table#log-transactions-list-'+transactionId);
        var operationsList = container.find('tr');        
        if (operationsList.length<1) {
            var url = '/zfmyadmin/data/get-log-transaction-operations';
            var data = {"transaction_id":transactionId}
            $.getJSON(url, data, function(response){
                if(response.success) {
                    container.html(response.html);
                }                
            })
        } else {
            container.toggle();
        }
    })

    

    $('.log-transaction-button').toggle(function(){
        if ($(this).hasClass('active-list'))
            {
                $(this).removeClass('active-list');
            }
        else
            {
                $(this).addClass('active-list');
            }
        
    }, function() {
        if ($(this).hasClass('active-list'))
            {
                $(this).removeClass('active-list');
            }
        else
            {
                $(this).addClass('active-list');
            }
    })
    
    $('#read-code-window').dialog({
            autoOpen: false,
            open: function(event, ui) {  },
            modal: true,
            width: '80%',
            dialogClass: 'ui-dialog-custom-settings'
    });
    
   
    $('.action-info-read-code').live('click', function(){
        var container = $('#read-code-window');        
        container.find('div.operation-details-field').html(''); 
        var url = "/zfmyadmin/data/get-operation-detail";        
        var data = {'operationId': $(this).attr('data-id')};
        
        if($(this).attr('data-type')=='intention') {
            data.intention = $('#intention-signature').val();
        } 

        $.getJSON(url, data, function(response) {
            container.find('div#operation-details-description').html(response.description);
            var disabled = [];
            if(response.showCode) {
                container.find('div#operation-details-code').html(response.showCode );               
            } else {
                disabled.push(1);
            }
            if(response.showFullCode) {
                container.find('div#operation-full-details-code').html(response.showFullCode);
            } else {
                disabled.push(2);
            }
            container.dialog('open');
            $('#operation-info-tabs').tabs( "select" , 'operation-details-description');
            //function blocks tab if it is empty
            $('#operation-info-tabs').tabs({
               'disabled' : disabled  
            }); 
            /*Scroll to generated code*/
            var top = 0; 
            container.find('pre.highlight-code').css('backgroundColor','#ffffff')
            $('#operation-info-tabs').tabs({'show': function(event,ui){                    
                if(top == 0) {
                    var position = container.find('pre.highlight-code').position();
                    top = parseInt(position.top); 
                }   
                container.find('div#operation-full-details-code').animate(
                    {scrollTop:top},
                    750,
                    function(){
                        container.find('pre.highlight-code').animate({
                            backgroundColor:"#E4E4E4"
                        },500
                    )
                })
            }})
            
            var clip = new ZeroClipboard.Client();
            ZeroClipboard.setMoviePath('/zfmyadmin_public/libs/zeroclipboard/ZeroClipboard.swf');
            clip.setText('');
            clip.glue('copyButton');
            $('#copyButton').hide();
            
            $('#operation-info-tabs').tabs({
                'select': function(event, ui) {
                   var selected = ui.index;
                   if (selected !== 0) {
                        $('#copyButton').show();
                      } else {
                        $('#copyButton').hide();
                      }
                }
            });
            


            clip.addEventListener( 'onMouseDown', copyToClipboard )
            var notifyText = $('#copyNotifier').text();

            function copyToClipboard(client) {
                    var pasteAddr = $('#operation-info-tabs .ui-state-active a').attr('href');
                    $('#copyNotifier').text(notifyText);
                    $('#copyNotifier').css({'display':'none','width':'80%','height':'190px','left':'0', 'padding':'100px'});
                    if (pasteAddr !== "#operation-details-description") {
                        var pasteText = $(pasteAddr).text();
                        clip.setText( pasteText );
                        var pasteHTML = $(pasteAddr).html();
                        
                        $('#copyNotifier').css('display','block');

                        setTimeout(function(){
                            $('#copyNotifier').text('')
                            $('#copyNotifier').animate({'width':'10px','height':'2px','left':'95%', 'padding':'5px'}, 300, function() {
                                $('#copyNotifier').fadeOut(10);
                            });
                    
                        },700)                        
                    }
                    
            }

        });
        
    })
})