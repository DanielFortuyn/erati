$(document).ready(function(){
    checkMessages("success");
    checkMessages("comment");
    checkMessages("error");
    $( "#search" ).catcomplete({
            delay: 0,
            source: 'home_ajax/suggestions/',
            select: function(event,ui)  {
                if(ui.item.category == 'Klanten')   {
                    window.location = 'klant/detail/id/' + ui.item.id; 
                }   else if(ui.item.category == 'Leveranciers') {
                    window.location = 'leverancier/detail/id/' + ui.item.id; 
                }   else if(ui.item.category == 'Product') {
                    window.location = 'product/detail/id/' + ui.item.id; 
                }   else if(ui.item.category == 'Prospect') {
                    window.location = 'prospect/detail/id/' + ui.item.id; 
                }
            },
            minLength:3
    });
    $('.topnav li').hover(function()    {
        $('ul',this).slideDown('fast');
    },function()    {
       $('ul',this).slideUp('fast'); 
    })
    $('#numNotify').corner('3px');
    $( "#tabs" ).tabs();
});

function checkMessages(name)    {
    if($('#' + name).html() != "")  {
        $('#' + name).slideDown('fast');
        setTimeout(function() {
            $('#' + name).slideUp('fast', function() {
		$('#' + name).empty();
            });
	}, (5500));
    }
}
function addMessage(name,msg,timeout)   {
    timeout = (timeout == undefined) ? 3500 : timeout;

    $('#' + name).append(msg);
    $('#' + name).slideDown('fast',function()   {
        setTimeout(function() {
            $('#' + name).slideUp('fast', function() {
		$('#' + name).empty();
            });
	}, (timeout));
    });
}
$.widget( "custom.catcomplete", $.ui.autocomplete, {
        _renderMenu: function( ul, items ) {
                var self = this,
                        currentCategory = "";
                $.each( items, function( index, item ) {
                        if ( item.category != currentCategory ) {
                                ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
                                currentCategory = item.category;
                        }
                        self._renderItem( ul, item );
                });
        }
});
function deleteDoc(url,id)   {
    var bool = confirm('Wilt u dit document PERMANENT verwijderen?');
    if(bool)    {
        $.ajax({
            url: url,
            dataType: 'script'
        });
    }
}
function getMoreNotes(type,id,numnotes)	{
    $.get('notitie_ajax/getNote/type/' + type + '/fid/' + id + '/numnotes/' + numnotes,function(data)   {
	$('#notitie').append(data);
    });

    
}
function conf(loc,msg) {
	if(msg == '')	{
		msg = 'Weet u zeker dat u dit item wilt verwijderen?';
	}
	var answer = confirm(msg);
	if (answer){
		window.location = loc;
	}
}