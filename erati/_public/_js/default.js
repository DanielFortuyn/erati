function cf(e)   {
    $('#' + e.id).val('');
    $('#' + e.id).removeClass('inactive');
}
function ff(e,val)  {
   var id = e.id;

   if($('#' + id).val() == '' || $('#' + id).val() == val) {
       $('#' + id).val(val);
       $('#' + id).addClass('inactive');
   }    
}
