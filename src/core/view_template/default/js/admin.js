// FOR IMAGE BASE64 RESIZE
function resizeImage(base64Str, maxWidth, maxHeight) {
	return new Promise((resolve) => {
	  let img = new Image()
	  img.src = base64Str
	  img.onload = () => {
		let canvas = document.createElement('canvas')
		let width = maxWidth;
		let height = maxHeight;
		canvas.width = width
		canvas.height = height
		let ctx = canvas.getContext('2d')
		ctx.drawImage(img, 0, 0, width, height)
		resolve(canvas.toDataURL())
	  }
	})
  }

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

    $('.mobile-toggle').on('click', function(e) {
        e.preventDefault();

        var mobileNav = $('.mobile-nav');

        mobileNav.addClass('show');
        mobileNav.find('> .mobile-nav-inner').addClass('opened');
    });

    $(document).on('click', '.mobile-nav.show', function(e) {
        e.stopPropagation();

        if (e.target !== e.currentTarget) return;

        var mobileNav = $('.mobile-nav');

        mobileNav.find('> .mobile-nav-inner').removeClass('opened');
        setTimeout(function() {
            mobileNav.removeClass('show');
        }, 530);
    });
    
});