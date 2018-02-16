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
});