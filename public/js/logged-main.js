// Onload
$(document).ready(function()
{
    // Mobile : Sidebar show
    $(".mobileSidebarOpen").on('click', function(e)
    {
        e.preventDefault();
        e.stopPropagation();

        // Func
        if(!$(".sidebarMain").hasClass("open"))
        {
            if($(window).width() <= 340)
            {
                $(".website").animate({"right": "260px"}, function ()
                {
                    $(".sidebarMain").addClass("open");
                    $("body").css("overflow", "hidden");
                });
            }else{
                $(".website").animate({"right": "300px"}, function ()
                {
                    $(".sidebarMain").addClass("open");
                    $("body").css("overflow", "hidden");
                });
            }
        }else{
            $(".website").animate({"right": "0px"}, function ()
            {
                $(".sidebarMain").removeClass("open");
                $("body").css("overflow", "auto");
            });
        }
    });

    var busy = false;

    // Send anon
    $(document).on('submit', '#incogMessageMaker', function(e){
        if(busy == false)
        {
            // Prevent
            e.preventDefault();

            busy = true;

            // Vars
            var t = $(this);

            var action = t.attr('action');

            var token = $("#token");
            var message = $("#message");
            var checkbox = $("#anonymous");
            var usi = $("#usi");

            // Check
            if(message.val() != "")
            {
                $.post(action, {_token: token.val(), message: message.val(), anonymous: checkbox.val(), usi: usi.val()}, function(data)
                {
                    var obj = jQuery.parseJSON(data);

                    if(obj.code == 1)
                    {
                        alert(obj.message);
                        busy = false;
                    }else{
                        alert(obj.message);
                        busy = false;
                    }
                });
            }else{
                message.css('border', '2px solid #d63031');
                busy = false;
            }
        }

        return false;
    });

    // Hide anon
    $(document).on('click', '.hideAnon', function()
    {
        if(busy == false)
        {
            busy = true;

            //e.preventDefault();

            var id = $(this).data('id');
            var token = $(this).data('token');

            var href = $(this).attr('href');

            if (id != "" && token != "")
            {
                $.post(href, {incogid: id}, function (data) {
                    var obj = jQuery.parseJSON(data);

                    if (obj.code == 1) {
                        $("#message" + id).fadeOut('slow');
                    } else {
                        alert(obj.status);
                    }

                    busy = false;
                });
            }

            return false;
        }
    });

    // Reply to anon
    $(document).on('click', '.anonActionBtn', function(e){
        e.preventDefault();

        var t = $(this);

        // Vars
        var action = t.data('action');
        var anonId = t.data('anonid');
        var message = $("#message" + anonId);
        var replyBox = $("#replyBox" + anonId);

        if(action === "showReplyBox")
        {
            if(message.hasClass('replyActive'))
            {
                message.removeClass('replyActive');
                replyBox.css('display', 'none');
            }else{
                message.addClass('replyActive');
                replyBox.css('display', 'block');
            }
        }
    });
});