// Onload
$(document).ready(function()
{
    // Mobile : Notifications box (Close)
    $(document).on('click touchstart', '.closeNoteBoxMobile', function(){
        var noteBox = $(".mobileNotificationHold");
        noteBox.fadeOut('fast');
        $(".boxOverlay").fadeOut('fast');
        $("html, body").css('overflow-y', 'auto');
    });

    // Mobile : Notifications box (Open)
    $(document).on('click touchstart', '.notificationOpen', function(e){
        e.preventDefault();
        var noteBox = $(".mobileNotificationHold");
        noteBox.fadeIn('fast');
        $(".boxOverlay").fadeIn('fast');
        $("html, body").css('overflow-y', 'hidden');
    });

    // Mobile : Search box (Close)
    $(document).on('click touchstart', '.closeSearchBoxMobile', function(){
        var searchBox = $(".mobileSearchHold");
        searchBox.fadeOut('fast');
        $(".boxOverlay").fadeOut('fast');
        $("html, body").css('overflow-y', 'auto');
    });

    // Mobile : Search box (Open)
    $(document).on('click touchstart', '.searchOpen', function(e){
        e.preventDefault();
        var searchBox = $(".mobileSearchHold");
        searchBox.fadeIn('fast');
        $(".boxOverlay").fadeIn('fast');
        $("html, body").css('overflow-y', 'hidden');
    });

    // Mobile : Search live
    $(document).on('keyup', '.searchMainInput', function() {
        if (busy == false)
        {
            busy = true;

            // Var
            var action = $("#searchMain").attr('action');
            var token = $(this).data('token');
            var input = $(this);

            // Empty
            var results = $(".bottomSearchResults");
            results.html("");

            if(input.val() != "")
            {
                $.post(action, {_token: token, searchMainInput: input.val()}, function(data){
                    var obj = jQuery.parseJSON(data);

                    if(obj.code == 1)
                    {
                        // Iterate
                        $.each(obj.data, function(key, value)
                        {
                            //value.name
                            var temp = "";

                            temp += "<div class='note'>";
                                temp += "<div class='leftProfile'>";
                                    temp += "<div class='pp' style='background-image: url("+obj.url+"/user/"+value.unique_salt_id+"/profile_picture)'></div>";
                                temp += "</div>";
                                temp += "<div class='rightBody'>";
                                    temp += "<h3><a href='"+obj.url+"/p/"+value.username+"'>"+value.name+"</a></h3>";
                                    temp += "<p>@"+value.username+"</p>";
                                temp += "</div>";
                            temp += "</div>";

                            // Append
                            results.append(temp);
                        });

                        busy = false;
                    }else{
                        busy = false;
                    }
                });
            }else{
                busy = false;
            }
        }
    });

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

                // Focus
                $("#replyInput" + anonId).focus();
            }
        }
    });

    $(document).on('submit', '.replyMakerForm', function(e){
       e.preventDefault();

       if(busy == false)
       {
           busy = true;

           // Vars
           var form = $(this);
           var mid = form.data('id');
           var action = form.attr('action');

           var replyInput = form.find('.replyInput');
           replyInput.css('border', 'none');

           var replyId = form.find('.replyId');

           var replyBoxHold = $("#replyBoxHold" + mid);

           var token = form.find('.replyToken');

           // Validation
           if(replyInput.val() != "" && replyId.val() != "")
           {
                $.post(action, {_token: token.val(), id: replyId.val(), message: replyInput.val()}, function(data){
                    var obj = jQuery.parseJSON(data);

                    if(obj.code == 1)
                    {
                        // Init temp
                        var temp = "";

                        // Create temp
                        temp += "<div class='reply'>";
                            temp += "<div class='leftProfile'>";
                                temp += "<div class='innerPP' style='background-image: url("+ obj.data.url +"/user/"+obj.data.usi+"/profile_picture);'></div>";
                            temp += "</div>";
                            temp += "<div class='rightProfile'>";
                                temp += "<h3><a href='" + obj.data.url + "/p/"+ obj.data.username +"'>" + obj.data.name + "</a> &middot; <span>Just now</span></h3>";
                                temp += "<p>" + obj.data.message + "</p>";
                            temp += "</div>";
                        temp += "</div>";

                        // Append
                        replyBoxHold.children('.row').append(temp);
                        replyBoxHold.children('.row').last().focus();

                        // Empty box
                        replyInput.val("");

                        busy = false;
                    }else{
                        alert(obj.message);
                        busy = false;
                    }
                });
           }else{
               replyInput.css('border', '2px solid #d63031');
               busy = false;
           }
       }
    });
});