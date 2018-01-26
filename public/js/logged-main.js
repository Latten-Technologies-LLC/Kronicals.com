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
});

// Right off
$(function(){
    
    
});