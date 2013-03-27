$(function() {
/***** Uninstall button *****/

$('#form-install-confirm').prop('checked', false);
$('#form-install-submit').attr('disabled', 'disabled');

$('#form-install-confirm').change(function(){
     if ($('#form-install-confirm').prop('checked'))
         {
             $('#form-install-submit').removeAttr('disabled');
         }
     else
         {
             $('#form-install-submit').attr('disabled', 'disabled');
         }
})


});