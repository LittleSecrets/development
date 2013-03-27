$(function() {
    $('#user-settings-tabs').tabs();
    $('#user-settings-doc-tabs').tabs();



   var prev, name, value;
   $('.switcher').toggle(function(){
       $(this).addClass('active-switcher');
       prev = $(this).next('input');
       value = prev.val();
       name = prev.attr('name');

       prev.css('display', 'none');

       $('#form-user-password').after('<input id="visible-pass" type="text" name="' + name + '" value="">');
       $('#visible-pass').val(value);

   }, function(){
       $(this).removeClass('active-switcher');
       $('#visible-pass').remove();
       prev.css('display', 'inline')
   })

});

