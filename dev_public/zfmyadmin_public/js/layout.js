$(function() {
   $('#lang').hover(function(){
       $('#lang-all').css('display', 'block');
        $('#lang-all').mouseover(function(){
            $(this).css('display', 'block');
        })
        $('#lang-all').mouseout(function(){
            $(this).css('display', 'none');
        })
   }, function(){
       $('#lang-all').css('display', 'none');
   });

   $('#flash-message').fadeOut(4000);

});



