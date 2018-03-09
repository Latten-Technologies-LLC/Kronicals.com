<?php
$stylesheet = "timeline";

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>
@extends('layouts.logged-in-main')

@section('content')
    <div class="container timelineMiddle">
        <div class="inner row">
            <!-- Left main feed -->
            <div class="leftFeed col-xl-9 col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="card profileLink d-block d-sm-none" style="margin-bottom: 10px;">
                    <div class="cardTop" style="padding: 10px;">
                        <h3><i class="fas fa-link"></i> Profile Link</h3>
                    </div>
                    <div class="cardBottom">
                        <a href="<?php url('/'); ?>/p/<?php echo auth()->user()->username; ?>"><span>anonuss.com/p/<?php echo auth()->user()->username; ?></span></a>
                    </div>
                </div>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div><br />
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div><br />
                @endif
                <div class="top">
                    <ul>
                        <li class="active"><a href="{{ route('timeline.index') }}"><h3>Timeline</h3></a></li>

                        <?php
                            $count = count($incog->unreadCount(auth()->user()->unique_salt_id));
                        ?>
                        <div class="rightLinks float-right">
                            <li><a href="{{ route('timeline.anons') }}"><h3>Anons <?php if($count > 0){ ?><span style="position: relative;top: -1px;" class="badge badge-pill badge-danger"><?php echo $count; ?></span><?php } ?></h3></a></li>
                            <li><a href="{{ route('timeline.sent') }}"><h3>Sent</h3></a></li>
                        </div>
                    </ul>
                </div>
                <!-- Beta 1.1: <div class="bottom card-columns"> -->
                <div class="bottom" style="column-count: 1;">
                    <div class="card" style="padding: 25px;border: none;box-shadow: 0 3px 6px rgba(0,0,0,0.12), 0 3px 6px rgba(0,0,0,0.14);">
                        <h3 style="text-align: center;margin: 0px;">Coming soon in beta 1.2</h3>
                    </div>
                    <div class="topPostingStation">

                    </div>
                    <div class="bottomTimelineFeed">

                    </div>
                </div>
            </div>

            <!-- Right sidebar -->
            @include('timeline.templates.sidebar')
        </div>
    </div>

    <!-- Stuff -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Remove Ads</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo route('braintree.disablead'); ?>" method="post">
                    <div class="modal-body" style="padding: 15px;">
                        <div class="mainBody" style="border-bottom: 1px solid #eee;">
                            <p>Ads support us and keeps us going! You can still support us even if you want to remove the ads</p>
                            <b><h5>Price: $2</h5></b>
                        </div><br />
                        <h5>Payment Methods</h5><br />
                        <div class="payment" id="payment"></div>

                       <!-- <br />
                        <h5>Billing Address</h5> -->
                    </div>
                    <div class="modal-footer">
                        {{ csrf_field() }}
                        <a type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                        <input type="submit" class="btn btn-primary" value="Remove Ads">
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
    @section('scripts')
    <script src="https://js.braintreegateway.com/js/braintree-2.32.1.min.js"></script>
    <script type="text/javascript">
        $(function () {
            // Get token
            $.get("{{ route('braintree.token') }}", function(data){
                var obj = jQuery.parseJSON(data);
                braintree.setup(obj.token, 'dropin', {
                    container: 'payment'
                });
            });

            <?php
            if(isset($_GET['m'])){
            ?>
                // Scroll to message
                $('html, body').animate({
                    scrollTop: $("#message<?php echo $_GET['m']; ?>").offset().top
                }, 2000, function(){
                    $("#message<?php echo $_GET['m']; ?>").addClass('replyActive');
                    $("#replyBox<?php echo $_GET['m']; ?>").fadeIn('fast');
                });

            <?php
            }
            ?>
        });
    </script>
    @endsection