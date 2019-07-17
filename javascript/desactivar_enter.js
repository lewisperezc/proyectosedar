$(document).ready(function() {
    $("form").keypress(function(e) {
        if (e.which == 13) {
            return false;
        }		
    });
});



function DesactivarBotonAtras()
{
	window.location.hash="no-back-button";
	window.location.hash="Again-No-back-button" //chrome
	window.onhashchange=function(){window.location.hash="no-back-button";}
}
/*
if(window.event){
key = window.event.keyCode; //IE
}else{
key = e.which; //firefox
}
if(key==13){
return false;
}else{
return true;
}*/