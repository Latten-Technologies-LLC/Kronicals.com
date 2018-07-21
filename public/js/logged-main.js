$(function(){
    var options =
    {
        //icon: window.app_icon,
        title: window.app_name
    };

    // Mobile rendering
    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };

    // Alert box
    $.fn.Notify = function (title, message, sentBy, type) {
        var t = $(this);
        if (message != "" && type != "") {
            if (t.hasClass('hidden')) {
                t.removeClass('hidden');
                if (t.hasClass('slideOutUp')) {
                    t.removeClass('slideOutUp');
                }
                t.addClass('slideInDown');
            }
            switch (type) {
                case 'notification':
                    // Icon
                    t.find('.profilePicture').attr('style', 'background-image: url(/user/' + sentBy + '/profile_picture);');

                    // Title
                    t.find('.notificationTitle').html(title);

                    // Body
                    t.find('.notificationBody').html(message);

                    setTimeout(function () {
                        t.removeClass('global_success');
                        if (t.hasClass('slideInDown')) {
                            t.addClass('slideOutUp');
                            t.addClass('hidden');
                        }
                    }, 6000);
                    break;
            }
        }
    };

    $(".closeBodyAlert").on('click', function(){
        var t = $(".body-alert");

        t.removeClass('global_success');
        if (t.hasClass('slideInDown')) {
            t.addClass('slideOutUp');
            t.addClass('hidden');
        }
    });

    // Setup Browser notifications
    if (!("Notification" in window))
    {
        console.log("This browser does not support desktop notification");
    }else{
        // Options
        options =
        {
            //icon: window.app_icon,
            title: window.app_name
        };

        // Check permission
        if (Notification.permission === "granted")
        {
            // Do nothing...
        }else if (Notification.permission !== "denied")
        {
            Notification.requestPermission(function (permission)
            {
                // If the user accepts, let's create a notification
                if (permission === "granted") {
                    options = "Awesome! You're now setup to receive notifications through your browser! Enjoy";
                    var notification = new Notification("Welcome!", options);
                }
            });
        }
    }

    // Notifying people (Post Likes)
    window.channel.bind('App\\Events\\PostLiked', function(data) {
        if (!("Notification" in window) && Notification.permission === "granted" && !isMobile.any()){
            options.body = data.message;
            options.icon = '/user/' + data.sent_from_data[0].unique_salt_id + '/profile_picture';
            var notification = new Notification(data.sent_from_data[0].name + ' liked your post', options);
        }

        // Alert
        $(".body-alert").Notify(data.sent_from_data[0].name, 'Liked your post!', data.sent_from_data[0].unique_salt_id, 'notification');
    });

    // Notifying people (Post Comments)
    window.channel.bind('App\\Events\\PostComment', function(data)
    {
        if (!("Notification" in window) && Notification.permission === "granted" && !isMobile.any()) {
            options.body = data.message;
            options.icon = '/user/' + data.sent_from_data[0].unique_salt_id + '/profile_picture';
            var notification = new Notification(data.sent_from_data[0].name + ' commented on your post', options);
        }

        // Alert
        $(".body-alert").Notify(data.sent_from_data[0].name, 'Commented on your post!', data.sent_from_data[0].unique_salt_id, 'notification');
    });

    // Notifying people (New Follower)
    window.channel.bind('App\\Events\\NewFollower', function(data)
    {
        if (!("Notification" in window) && Notification.permission === "granted" && !isMobile.any()) {
            options.body = data.message;
            options.icon = '/user/' + data.sent_from_data[0].unique_salt_id + '/profile_picture';
            var notification = new Notification(data.sent_from_data[0].name + ' just followed you!', options);
        }
        // Alert
        $(".body-alert").Notify(data.sent_from_data[0].name, 'Followed you!', data.sent_from_data[0].unique_salt_id, 'notification');
    });
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

                // Clear text
                $("#postingStationText").val("");
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
            }else if(type.val() == 3)
            {
                // Put text in var
                var htmlText = editor.root.innerHTML;
                var title = $("#diary_title_input");

                if(title.val() != "")
                {
                    if (htmlText != "" && htmlText != "<p></p>")
                    {
                        $.post(action, {text: htmlText, title: title.val(), type: "3"}, function (data)
                        {
                            var obj = jQuery.parseJSON(data);

                            if (obj.code == 1) {
                                //htmlText.html("");
                                window.location.assign('/diary/view/' + obj.id);
                            } else {
                                alert(obj.message);
                                busy = false;
                            }
                        });
                    } else {
                        busy = false;
                    }
                }else{
                    alert('Please enter a title');
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

    // Diary functions
    $(document).on('click', '.diaryActions', function()
    {

        // Var
        var t = $(this);

        var type = t.data('type');
        var pid = t.data('pid');
        var token = t.data('token');

        var action = t.data('action');

        if(type == "delete")
        {
            if (pid != "")
            {
                $.post(action, {pid: pid, _token: token}, function (data)
                {
                    var obj = jQuery.parseJSON(data);

                    if (obj.code == 1) {
                        // We're in business
                        window.location.assign('/diary');

                        busy = false;
                    } else {
                        alert(obj.message);
                        busy = false;
                    }
                });
            } else {
                alert("Invalid request");
                busy = false;
            }
        }else if(type == "convert")
        {
            if (pid != "")
            {
                $.post(action, {pid: pid, _token: token}, function (data)
                {
                    var obj = jQuery.parseJSON(data);

                    if (obj.code == 1) {
                        // We're in business
                        alert(obj.message);

                        busy = false;
                        return false;
                    } else {
                        alert(obj.message);
                        busy = false;
                    }
                });
            } else {
                alert("Invalid request");
                busy = false;
            }
        }
    });
    
    
});