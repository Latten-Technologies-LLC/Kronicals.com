$(function(){
    //var socket = io('http://anonuss.dev:3000');

    //socket.on("userSignupChannel:App\\Events\\NewUserSignup", function(message){
        // increase the power everytime we load test route
    //    alert('OK');
    //});
});

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
        
        // Now do the ajax stuff
        var action = $(this).attr('href');
        $.post(action, {_token: $(this).data('token')}, function(data){
            $(".notePill").fadeOut('fast');
        });
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

    // Confess anon
    $(document).on('click', '.confessAnon', function()
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
                $.post(href, {id: id}, function (data) {
                    var obj = jQuery.parseJSON(data);

                    if (obj.code == 1) {
                        alert(obj.status);
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

    // Follow system
    $(document).on('click', '.followBtn', function(e){
       e.preventDefault();
        
       if(busy == false)
       {
           busy = true;

           // Var
           var t = $(this);

           var action = t.attr('href');
           var uid = t.data('id');
           var token = t.data('token');

           // Run
           if(action != "" && uid != "")
           {
               $.post(action, {_token: token, uid: uid}, function(data){
                   var obj = jQuery.parseJSON(data);

                   if(obj.code == 1)
                   {
                       // Reload
                       location.reload();
                   }else{
                       alert(obj.status);

                       busy = false;
                       return false;
                   }
               });
           }else{
               alert('Error occurred');

               busy = false;
               return false;
           }
       }
       return false;
    });

    $(document).on('click', '.unfollowBtn', function(e){
        e.preventDefault();

        if(busy == false)
        {
            busy = true;

            // Var
            var t = $(this);

            var action = t.attr('href');
            var uid = t.data('id');
            var token = t.data('token');

            // Run
            if(action != "" && uid != "")
            {
                $.post(action, {_token: token, uid: uid}, function(data){
                    var obj = jQuery.parseJSON(data);

                    if(obj.code == 1)
                    {
                        // Reload
                        location.reload();
                    }else{
                        alert(obj.status);

                        busy = false;
                        return false;
                    }
                });
            }else{
                alert('Error occurred');

                busy = false;
                return false;
            }
        }
        return false;
    });

    // For privacy settings on timeline page
    $(document).on('click', '.openPrivTab', function(){
        var privDrop = $(".privacyDrop");

        if(privDrop.hasClass("hidden"))
        {
            privDrop.removeClass('hidden');
        }else{
            privDrop.addClass('hidden');
        }
    });

    $(document).on('click', '.privTabSetting', function(){
        var t = $(this);

        var currentPriv = $("#def-privacy").val();
        var thisPriv = t.data('priv');

        if(thisPriv != "")
        {
            if(thisPriv == 3) {
                $(".currentSetting").html(t.data('val'));
                $("#def-privacy").val(thisPriv);

                $(".privTabSetting").removeClass("privActive");
                t.addClass("privActive");

                $(".privacyDrop").addClass('hidden');

                // Hide main textarea
                $(".normal_entry").addClass('hidden');
                $(".diary_entry").removeClass('hidden');
            }else {
                $(".currentSetting").html(t.data('val'));
                $("#def-privacy").val(thisPriv);

                $(".privTabSetting").removeClass("privActive");
                t.addClass("privActive");

                $(".privacyDrop").addClass('hidden');

                // Hide main textarea
                $(".normal_entry").removeClass('hidden');
                $(".diary_entry").addClass('hidden');
            }
        }
    });

    // Posting from the timeline
    $(document).on('click', '.innerPostingStation', function(event){
        var p = $("#postingStationText");
        var bottom = $(".bottomArea");

        if(bottom.hasClass('hidden'))
        {
            // Resize
            p.animate({'height':'120px'}, function(){
                bottom.removeClass('hidden');
            });
        }

        event.stopPropagation();
    });

    $(document).click(function(){
        var p = $("#postingStationText");
        var bottom = $(".bottomArea");

        if(!bottom.hasClass('hidden'))
        {
            // Resize
            p.animate({'height':'50px'}, '', '', function(){
                bottom.addClass('hidden');
            });
        }
    });

    var toolbarOptions = [[{ 'header': [1, 2, 3, 4, 5, 6, false] }],[{ 'list': 'ordered'}, { 'list': 'bullet' }], ['bold', 'italic', 'underline', 'strike'], [{ 'align': [] }], ['image','link','blockquote']];

    // Main editor
    var editor = new Quill('.main_diary_entry', {
        modules: {
            toolbar: toolbarOptions,    // Snow includes toolbar by default
        },
        placeholder: 'Whats on your mind?',
        height: '100px',
        theme: 'snow'
    });

    $(document).on('submit', '#postingStation', function(e){
        if(busy == false)
        {
            busy = true;
            e.preventDefault();

            // Var
            var text = $("#postingStationText");
            var type = $("#def-privacy");
            var action = $(this).attr('action');

            if(text.val() != "" && type.val() != "")
            {
                if(type.val() == 3)
                {
                    // Put text in var
                    var htmlText = editor.root.innerHTML;

                    if(htmlText != "" && htmlText != "<p></p>")
                    {
                        $.post(action, {text: htmlText, type: "3"}, function (data)
                        {
                            var obj = jQuery.parseJSON(data);

                            if (obj.code == 1)
                            {
                                //htmlText.html("");
                                location.reload();
                            } else {
                                alert(obj.message);
                                busy = false;
                            }
                        });
                    }else{
                        busy = false;
                    }
                    return false;
                }else {
                    $.post(action, {text: text.val(), type: type.val()}, function (data)
                    {
                        var obj = jQuery.parseJSON(data);

                        if (obj.code == 1)
                        {
                            text.val("");
                            location.reload();
                        } else {
                            alert(obj.message);
                            busy = false;
                        }
                    });
                }
            }else if(type.val() == 3)
            {
                // Put text in var
                var htmlText = editor.root.innerHTML;

                if(htmlText != "" && htmlText != "<p></p>")
                {
                    $.post(action, {text: htmlText, type: "3"}, function (data)
                    {
                        var obj = jQuery.parseJSON(data);

                        if (obj.code == 1)
                        {
                            //htmlText.html("");
                            location.reload();
                        } else {
                            alert(obj.message);
                            busy = false;
                        }
                    });
                }else{
                    busy = false;
                }
                return false;
            } else
            {
                busy = false;
            }
        }
        return false;
    });

    // Post actions
    $(document).on('click', '.postAction', function(e)
    {
        e.preventDefault();

        if(busy == false)
        {
            busy = true;

            // Var
            var t = $(this);
            var href = t.children('a');
            var sibling = t.siblings();

            var type = t.data('type');
            var pid = t.data('pid');
            var token = t.data('token');

            var action = href.attr('href');

            var count = href.children('.count');
            var scount = sibling.children('a').children('.count');

            if(type == "like" || type == "unlike")
            {
                if(pid != "")
                {
                    // Call method
                    $.post(action, {pid: pid, _token: token}, function(data)
                    {
                        var obj = jQuery.parseJSON(data);

                        if(obj.code == 1)
                        {
                            // We're in business
                            count.html(obj.count);
                            scount.html(obj.count);

                            t.addClass('hidden');
                            sibling.removeClass('hidden');

                            busy = false;
                        }else{
                            alert(obj.message);
                            busy = false;
                        }
                    });
                }else{
                    alert("Invalid request");
                    busy = false;
                }
            }else if(type == "delete")
            {
                if(pid != "")
                {
                    var post = $("#post" + pid);

                    $.post(action, {pid: pid, _token: token}, function(data)
                    {
                        var obj = jQuery.parseJSON(data);

                        if(obj.code == 1)
                        {
                            // We're in business
                            post.fadeOut('slow');

                            busy = false;
                        }else{
                            alert(obj.message);
                            busy = false;
                        }
                    });
                }else{
                    alert("Invalid request");
                    busy = false;
                }
            } else if(type == "reply")
            {
                // Toggle stuff
                $("#postReplyBox" + pid).toggleClass('hidden');
                $("#postReplyInput" + pid).focus();
                busy = false;
            }
        }
    });
    
    // Make post reply
    $(document).on('submit', '.postReplyMakerForm', function(e){
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

            var replyBoxHold = $("#postReplyBoxHold" + mid);

            var token = form.find('.replyToken');

            // Validation
            if(replyInput.val() != "" && replyId.val() != "")
            {
                $.post(action, {_token: token.val(), pid: replyId.val(), message: replyInput.val()}, function(data){
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

    // Tutorial system
    $(document).on('click', '.tutorial_next_btn', function(){
       if(busy == false)
       {
           busy = true;

           // Var
           var current = $(this).data('current');
           var next = $(this).data('next');

           if(next != "")
           {
               // Hide all of them
               $(".tutorial_page").each(function(){
                  $(this).addClass('hidden');
               });

               // Now show the new one
               $("#tutorial_page" + next).removeClass('hidden');

               busy = false;
           }
       }
    });

    $(document).on('click', '.tutorial_skip_btn', function(){
        if(busy == false)
        {
            busy = true;

            // Var
            var tut = $(this).data('tut');

            if(tut != "")
            {
                $.post('/tutorials/update', {tut: tut}, function(data){
                    var obj = jQuery.parseJSON(data);

                    if(obj.code == 1)
                    {
                        $(".tutorial_overlay").fadeOut('fast');
                        $(".tutorial_main_hold").fadeOut('fast');
                    }
                });

                busy = false;
            }
        }
    });

    $(document).on('click', '.tutorial_finished_btn', function(){
        if(busy == false)
        {
            busy = true;

            // Var
            var tut = $(this).data('tut');

            if(tut != "")
            {
                $.post('/tutorials/update', {tut: tut}, function(data){
                    var obj = jQuery.parseJSON(data);

                    if(obj.code == 1)
                    {
                        $(".tutorial_overlay").fadeOut('fast');
                        $(".tutorial_main_hold").fadeOut('fast');
                    }
                });

                busy = false;
            }
        }
    });
});