$(document).ready(function(){
    $(".close-bar").click(function (){
        $("#container .left-bar").slideToggle('fast');
        $("")
    });
    window.onresize = function(event) {
        if($(window).width() > 650){
            $("#container .left-bar").show();
        }
    }
});
