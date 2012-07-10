$(document).ready(function(){
    $('body').keypress(function(e) {
	if(e.which == 13) {
	    $('#login_form').submit();
	}
    });
});