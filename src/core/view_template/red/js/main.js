//!AJAX for DEBUG
$(document).ready(function(){

    /* MDBootstrap Stuff */
    $('#no-waves .dropdown').removeClass('waves-effect');


    $("#debug-info").click(function() {
        $("#debug").hide();
    });

    $( document ).ajaxSuccess(function( event, request, settings )
    {
        $("#debug-ajax").append("<div><strong>ErrorType:</strong> " + event.type + "</div>");
        $("#debug-ajax").append("<div><strong>Error requesting page:</strong> " + settings.url + "</div>");
        responseText = request.responseText;
        $("#debug-ajax").append("<div><u>Message</u>:<br /><br /><pre>" + responseText + "</pre></div>");

        $("#debug").show();
    });

    $( document ).ajaxError(function( event, request, settings )
    {
        $("#debug-ajax").append("<div><strong>ErrorType:</strong> " + event.type + "</div>");
        $("#debug-ajax").append("<div><strong>Error requesting page:</strong> " + settings.url + "</div>");
        responseText = request.responseText;
        $("#debug-ajax").append("<div><u>Message</u>:<br /><br /><pre>" + responseText + "</pre></div>");
        $("#debug").show();
    });

    $(window).scroll(function () {
        if ($(this).scrollTop() > 500) {
            $('#back-to-top').fadeIn();
        } else {
            $('#back-to-top').fadeOut();
        }
    });
    // scroll body to 0px on click
    $('#back-to-top').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 400);
        return false;
    });
});