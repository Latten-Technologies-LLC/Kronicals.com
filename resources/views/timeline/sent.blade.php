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
                <div class="top">
                    <ul>
                        <li><a href="{{ route('timeline.index') }}"><h3>Anons</h3></a></li>
                        <li class="active"><a href="{{ route('timeline.sent') }}"><h3>Sent</h3></a></li>
                    </ul>
                </div>
                    <?php
                    $response = json_decode($sentmessages, true);

                    if($response['code'] == 1)
                    {
                    // Loop through all the messages
                    foreach($response['messages'] as $message)
                    {
                    if($message['hide'] == null or $message['hide'] != 1)
                    {
                    if($message['from_id'] != "")
                    {
                        $from = DB::table('users')->where('unique_salt_id', $message['from_id'])->get()[0];
                    }
                    ?>
                <div class="bottom card-columns">

                <div class="message card" id="message<?php echo $message['id']; ?>">
                        <div class="innerMessage">
                            <div class="topMessage">
                                <div class="leftProfile">
                                    <div class="pp" style="background-image: url(<?php echo url('/'); ?>/user/<?php echo $from->unique_salt_id; ?>/profile_picture);"></div>
                                </div>
                                <div class="rightProfile">
                                    <h3><a href="<?php echo url('/'); ?>/p/<?php echo $from->username; ?>"><?php echo $from->name; ?></a></h3>
                                    <h4 class="date"><?php echo time_elapsed_string($message['date']); ?></h4>
                                </div>
                            </div>
                            <div class="innerMessageMain">
                                <div class="topText">
                                    <p><?php echo Crypt::decrypt($message['message']); ?></p>
                                </div>
                                <div class="bottomText">
                                    <ul>
                                        <?php if($message['from_id'] != ""){ ?>
                                        <li><a data-anonid="<?php echo $message['id']; ?>" data-action="showReplyBox" class="anonActionBtn" href=""><i class="fas fa-reply"></i> Reply</a></li>
                                        <?php } ?>
                                        <li><a class="hideAnon" href="{{ route('incog.hide') }}" data-id="<?php echo $message['id']; ?>" data-t="{{ csrf_token() }}"><i class="far fa-eye-slash"></i> Hide</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="replyBox" id="replyBox<?php echo $message['id']; ?>">
                                <div class="replyMainHold">

                                </div>
                                <div class="replyMaker">
                                    <form action="" method="post" class="replyMakerForm">
                                        <div class="row">
                                            <div class="pp">
                                                <div class="mainpphold" style="background-image: url(<?php echo url('/'); ?>/user/<?php echo auth()->user()->unique_salt_id; ?>/profile_picture);"></div>
                                            </div>
                                            <div class="input">
                                                <input type="text" id="replyInput" placeholder="Press enter or return to reply" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <?php
                    }
                    }
                    }else{
                        ?>
                    }
                        <br />
                        <div class="message error">
                            <h3 style="text-align: center;"><?php echo $response['message']; ?></h3>
                        </div>
                    <?php
                    }
                    ?>
            </div>

            <!-- Right sidebar -->
            <div class="rightSidebar col-xl-3 col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <div class="card userMod d-xl-block d-lg-block d-md-block d-sm-none d-xs-none">
                    <div class="leftProfile pull-left">
                        <img src="<?php echo url('/'); ?>/user/<?php echo auth()->user()->unique_salt_id; ?>/profile_picture" />
                    </div>
                    <div class="rightProfile pull-left">
                        <h3><a href="<?php url('/'); ?>/p/<?php echo auth()->user()->username; ?>"><?php echo auth()->user()->name; ?></a></h3>
                        <h4>@<?php echo auth()->user()->username; ?></h4>
                    </div>
                </div><br />
                <div class="card notifications d-xl-block d-lg-block d-md-block d-sm-none d-xs-none">
                    <div class="cardTop">
                        <h3>Notifications</h3>
                    </div>
                    <div class="cardBottom">
                        <?php
                        foreach($notifications as $notification)
                        {
                        if($notification->user_from != null)
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
                                <h3><a href=""></a></h3>
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
                </div><br />
                <?php
                if(auth()->user()->remove_ads == 0)
                {
                ?>
                <div class="card ads">
                    <div class="cardTop">
                        <h3>Ad <a data-toggle="modal" data-target="#exampleModal" href="" class="" onClick="return false;">Remove Ads</a></h3>
                    </div>
                    <div class="cardBottom">

                    </div>
                </div>
                <?php
                }
                ?>
                <div class="no_footer">
                    <ul>
                        <li><a href="<?php //echo route(''); ?>">About</a></li>
                        <li><a href="<?php //echo route(''); ?>">Settings</a></li>
                        <li><a href="<?php //echo route(''); ?>">Logout</a></li>
                    </ul>
                    <span>&copy; 2018 Annonuss <br />Built by <a href="https://sitelyftstudios.com/">Sitelyft Studios</a></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stuff -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Remove Ads</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="padding: 15px;">
                        <div class="mainBody" style="border-bottom: 1px solid #eee;">
                            <p>Ads support us and keeps us going! You can still support us even if you want to remove the ads</p>
                            <b><h4>Price: $1</h4></b>
                        </div><br />
                        <h4>Payment Methods</h4><br />
                        <div class="payment" id="payment"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary">Remove Ads</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endsection
    @section('scripts')
    <script src="https://js.braintreegateway.com/js/braintree-2.32.1.min.js"></script>
    <script type="text/javascript">
        $(function () {
            // Get token
            $.get('{{ route('braintree.token') }}', function(data){
                var obj = jQuery.parseJSON(data);
                braintree.setup(obj.token, 'dropin', {
                    container: 'payment'
                });
            });
        });
    </script>
    @endsection