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
                        <li class="active"><h3>Anons</h3></li>
                        <li><h3>Sent</h3></li>
                    </ul>
                </div>
                <div class="bottom card-columns">
                    <?php
                        $response = json_decode($messages, true);

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
                                        <div class="message card" id="message<?php echo $message['id']; ?>">
                                            <div class="innerMessage">
                                                <div class="topMessage">
                                                    <div class="leftProfile">
                                                        <?php if($message['from_id'] != ""){ ?>
                                                            <div class="pp" style="background-image: url(<?php echo url('/'); ?>/user/<?php echo $from->unique_salt_id; ?>/profile_picture);"></div>
                                                        <?php } else { ?>
                                                            <img src="<?php echo url('/images'); ?>/default_pic.jpg" />
                                                        <?php } ?>
                                                    </div>
                                                    <div class="rightProfile">
                                                        <?php if($message['from_id'] != ""){ ?>
                                                            <?php if($message['anonymous'] == 0){ ?>
                                                                <h3><a href="<?php echo url('/'); ?>/p/<?php echo $from->username; ?>"><?php echo $from->name; ?></a></h3>
                                                            <?php } else { ?>
                                                                <h3>Anonymous</h3>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <h3>Anonymous</h3>
                                                        <?php } ?>
                                                        <h4 class="date"><?php echo time_elapsed_string($message['date']); ?></h4>
                                                    </div>
                                                </div>
                                                <div class="innerMessageMain">
                                                    <div class="topText">
                                                        <p><?php echo $message['message']; ?></p>
                                                    </div>
                                                    <div class="bottomText">
                                                        <ul>
                                                            <?php if($message['from_id'] != ""){ ?>
                                                                <li><a href=""><i class="fas fa-reply"></i> Reply</a></li>
                                                            <?php } ?>
                                                            <li><a class="hideAnon" href="{{ route('incog.hide') }}" data-id="<?php echo $message['id']; ?>" data-t="{{ csrf_token() }}"><i class="far fa-eye-slash"></i> Hide</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                }
                            }
                        }else{

                        }
                    ?>
                </div>
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
                <div class="card ads">
                    <div class="cardTop">
                        <h3>Ad <a href="">Remove Ads</a></h3>
                    </div>
                    <div class="cardBottom">

                    </div>
                </div>
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
@endsection