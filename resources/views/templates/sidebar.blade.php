<div class="sidebarMain">
    <div class="innerSidebar">
        <div class="bottomMenu">
            <ul itemscope='itemscope' itemtype='http://schema.org/SiteNavigationElement' role='navigation'>
                <?php if(Auth::check()){ ?>
                    <li itemprop='url' title="Settings"><a itemprop='name' href="<?php echo url("/"); ?>/settings">Settings</a></li>
                    <li itemprop='url' title="Learn about our services that we provide"><a itemprop='name' href="<?php echo url("/"); ?>/p/<?php echo auth()->user()->username; ?>">Profile</a></li>
                    <li class="special">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                        <a class="" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                    </li>
                <?php } else { ?>
                    <li itemprop='url' title="Learn who we are and what we do"><a itemprop='name' href="<?php echo url("/"); ?>/about">About Us</a></li>
                    <li itemprop='url' title="Learn about our services that we provide"><a itemprop='name' href="<?php echo url("/"); ?>/login">Login</a></li>
                    <li class="special"><a class="" href="<?php echo url("/"); ?>/register">Register</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>