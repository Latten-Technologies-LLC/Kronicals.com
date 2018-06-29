<!-- Right sidebar -->
<div class="rightSidebar col-xl-3 col-lg-3 col-md-3 col-xs-12 col-sm-12">
    <div class="card profileLink d-none d-sm-block" style="display: none !important;">
        <div class="cardTop" style="padding: 10px;">
            <h3><i class="fas fa-link"></i> Share your link</h3>
        </div>
        <div class="cardBottom">
            <a href="<?php url('/'); ?>/incog/<?php echo auth()->user()->username; ?>"><span>anonuss.com/incog/<?php echo auth()->user()->username; ?></span></a>
        </div>
    </div>
    <div class="card userMod d-none d-sm-block">
        <div class="leftProfile pull-left">
            <img src="<?php echo url('/'); ?>/user/<?php echo auth()->user()->unique_salt_id; ?>/profile_picture" />
        </div>
        <div class="rightProfile pull-left">
            <h3><a href="<?php url('/'); ?>/p/<?php echo auth()->user()->username; ?>"><?php echo auth()->user()->name; ?></a></h3>
            <h4>@<?php echo auth()->user()->username; ?></h4>
        </div>
    </div><br />
    <div class="card latestUsers d-none d-sm-block">
        <div class="cardTop">
            <h3>Latest Users</h3>
        </div>
        <div class="cardBottom">
            <?php
            $latest = DB::table('users')->orderBy('id', 'desc')->get();

            foreach($latest as $latest)
            {
            ?>
            <div class="note">
                <div class="leftProfile">
                    <div class="pp" style="background-image: url(<?php echo url('/'); ?>/user/<?php echo $latest->unique_salt_id; ?>/profile_picture);"></div>
                </div>
                <div class="rightBody">
                    <h3><a href="<?php echo url('/'); ?>/p/<?php echo $latest->username; ?>"><?php echo ucwords($latest->name); ?></a></h3>
                    <p style="font-size: .8em;"><a href="<?php echo url('/'); ?>/p/<?php echo $latest->username; ?>">Profile</a> &middot; <a href="<?php echo url('/'); ?>/incog/<?php echo $latest->username; ?>">Send Anon</a></p>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div><br />
    <?php
    if(auth()->user()->remove_ads == 0 && 1 == 2)
    {
    ?>
    <div class="card ads">
        <div class="cardTop">
            <h3>Ads <a data-toggle="modal" data-target="#exampleModal" href="" class="" onClick="return false;">Remove Ads</a></h3>
        </div>
        <div class="cardBottom">
            <?php if(env('APP_ENV') === 'production'){ ?>
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- Main Ad -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-1374725956270952"
                     data-ad-slot="1691788953"
                     data-ad-format="auto"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            <?php } ?>
        </div>
    </div>
    <?php
    }
    ?>
    <div class="no_footer">
        <ul>
            <li><a href="<?php echo route('about_us.index'); ?>">About</a></li>
            <li><a href="<?php echo route('settings.index'); ?>">Settings</a></li>
            <!-- <li><a href="<?php //echo route(''); ?>">Logout</a></li> -->
        </ul>
        <span>&copy; 2018 Annonuss <br />Built by <a href="https://sitelyftstudios.com/">Sitelyft Studios</a></span>
    </div>
</div>