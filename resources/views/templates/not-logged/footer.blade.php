<?php
 // Make sure we're including the full footer
if(!isset($no_footer) or $no_footer == false){
?>
<div class="footer">
    <div class="topFooter">
        <div class="container">
            <h3><a href="{{ route('register') }}">Need an account? Register here</a></h3>
        </div>
    </div>
    <div class="bottomFooter clearfix">
        <div class="topBottomFooter container">
            <h3><?php echo env('APP_NAME'); ?></h3>
        </div>
        <div class="middleNavigation">
            <ul>
                <li><a href="{{ route('about_us.index') }}">About Us</a></li>
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
            </ul>
        </div>
        <div class="bottomBottomFooter">
            <h3 class="copyright">Copyright &copy; 2017</h3>
        </div>
    </div>
</div>
<?php
}
?>
