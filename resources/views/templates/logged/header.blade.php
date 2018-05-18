<?php
use App\Libraries\Notifications;

$n = new Notifications();
?>
<div class="header logged-header absolute-top transparent container-fluid">
    <div class="inner-header container">
        <div class="left-branding pull-left">
            <h3 class=""><a href="<?php echo url('/'); ?>">Anonuss <?php if(env('APP_STATUS') == "Beta"){ ?><span style="font-weight: 500;position: relative;top: -3px;font-size: .4em;" class="badge badge-pill badge-light"><?php echo env('APP_STATUS'); ?> v<?php echo env('APP_VERSION'); ?></span><?php } ?></a></h3>
        </div>
        <div class="right-navigation pull-right">
            <div class="inner-navigation">
                <ul class="inner-navigation-list">
                    <div class="mobileHide">
                        <li><a href="{{ route('diary.index') }}">Diary</a></li>
                        <li><a href="{{ route('settings.index') }}">Settings</a></li>
                    </div>
                    <li><a href="{{ route('notifications.read') }}" data-token="{{ csrf_token() }}" class="notificationOpen nav-icon"><i class="far fa-bell"></i> <span class="<?php if(count($n->unreadNotifications(auth()->user()->unique_salt_id)) == 0){ ?>hidden<?php } ?> notePill badge badge-pill badge-danger"><?php echo count($n->unreadNotifications(auth()->user()->unique_salt_id)); ?></span></a></li>
                    <li><a href="" class="searchOpen nav-icon"><i class="fas fa-search"></i></a></li>
                    <li class="logged-pp-hold"><a href="<?php url('/'); ?>/p/<?php echo auth()->user()->username; ?>"><div class="logged-pp" style="background-image: url(<?php echo url('/'); ?>/user/<?php echo auth()->user()->unique_salt_id; ?>/profile_picture);"></div></a></li>
                    <li class="mobileSidebarOpen"><i class="fas fa-bars" aria-hidden="true"></i></li>
                </ul>
            </div>
        </div>
        <div class="mobileSearchHold">
            <div class="topName clearfix">
                <h3>Search</h3>
                <span class="closeSearchBoxMobile"><i class="fas fa-times-circle"></i></span>
            </div>
            <div class="bottomContent">
                <div class="topSearchBox">
                    <form action="{{ route('search.live') }}" method="post" id="searchMain">
                        <div class="form-group">
                            <input type="search" class="form-control searchMainInput" data-token="{{ csrf_token() }}" name="search" placeholder="Search" />
                        </div>
                    </form>
                </div>
                <div class="bottomSearchResults">

                </div>
            </div>
        </div>
        <div class="mobileNotificationHold">
            <div class="topName clearfix">
                <h3>Notifications</h3>
                <span class="closeNoteBoxMobile"><i class="fas fa-times-circle"></i></span>
            </div>
            <div class="bottomContent">
                <?php
                $notifications = $n->get(auth()->user()->unique_salt_id);

                foreach($notifications as $notification)
                {
                if($notification->user_from != 'null')
                {
                $from = DB::table('users')->where('unique_salt_id', $notification->user_from)->get()[0];
                }
                ?>
                <div class="note">
                    <div class="leftProfile">
                        <?php if($notification->user_from != null){ ?>
                        <?php if($notification->type == "incog"){ ?>
                        <img src="<?php echo url('/images'); ?>/default_pic.jpg" />
                        <?php } else { ?>
                        <div class="pp" style="background-image: url(<?php echo url('/'); ?>/user/<?php echo $from->unique_salt_id; ?>/profile_picture);"></div>
                        <?php } ?>
                        <?php } else { ?>
                        <img src="<?php echo url('/images'); ?>/default_pic.jpg" />
                        <?php } ?>
                    </div>
                    <div class="rightBody">
                        <?php if($notification->user_from != null){ ?>
                        <?php if($notification->type == "incog"){ ?>
                        <h3><b>Anonymous User</b></h3>
                        <?php } else { ?>
                        <h3><a href="<?php echo url('/'); ?>/p/<?php echo $from->username; ?>"><?php echo ucwords($from->name); ?></a></h3>
                        <?php } ?>
                        <?php } else { ?>
                        <h3><b>Anonymous User</b></h3>
                        <?php } ?>
                        <p><?php echo $notification->message; ?></p>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div><div class="boxOverlay"></div>