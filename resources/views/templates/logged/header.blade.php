<div class="header logged-header absolute-top transparent container-fluid">
    <div class="inner-header container">
        <div class="left-branding pull-left">
            <h3 class=""><a href="<?php echo url('/'); ?>">Anonuss</a></h3>
        </div>
        <div class="right-navigation pull-right">
            <div class="inner-navigation">
                <ul class="inner-navigation-list">
                    <div class="mobileHide">
                        <li ><a href="<?php echo url("/"); ?>/settings">Settings</a></li>
                    </div>
                    <li><a href="" class="nav-icon"><i class="far fa-bell"></i></a></li>
                    <li><a href="" class="nav-icon"><i class="fas fa-search"></i></a></li>
                    <li class="logged-pp-hold"><a href="<?php url('/'); ?>/p/<?php echo auth()->user()->username; ?>"><div class="logged-pp" style="background-image: url(<?php echo url('/'); ?>/user/<?php echo auth()->user()->unique_salt_id; ?>/profile_picture);"></div></a></li>
                    <li class="mobileSidebarOpen"><i class="fas fa-bars" aria-hidden="true"></i></li>
                </ul>
            </div>
        </div>
    </div>
</div>