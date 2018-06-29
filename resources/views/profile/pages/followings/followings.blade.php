<?php
$stylesheet = "profile";

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
            'y' => 'y',
            'm' => 'm',
            'w' => 'w',
            'd' => 'd',
            'h' => 'h',
            'i' => 'm',
            's' => 's',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . '' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

use Illuminate\Support\Facades\DB;

use App\Libraries\User;
use App\Libraries\FollowSystem;
use App\Libraries\PostingSystem;



// Follow system
$follow = new FollowSystem();
$postingsystem = new PostingSystem();

// Find user
$exists = User::exists($user[0]->id);

// Check
if(count($exists) == 1)
{
    // Then we good
    $profile = $user[0];
    $info = DB::table('user_info')->where('unique_salt_id', $profile->unique_salt_id)->get()[0];

    // Get poems
    $poems = DB::table("timeline_posts")->where('user_id', $user[0]->unique_salt_id)->where('type', '2')->orderBy('id', 'desc')->get();

    // Get posts
    $posts = DB::table("timeline_posts")->where('user_id', $user[0]->unique_salt_id)->where('type', '1')->orderBy('id', 'desc')->get();

    // Get followers
    $followers = DB::table("followings")->where('followee_id', $user[0]->unique_salt_id)->get();

    // Get followers
    $followings = DB::table("followings")->where('follower_id', $user[0]->unique_salt_id)->get();

    // Get views

}else{
    redirect('/');
}
?>
@extends('layouts.logged-in-main')

@section('content')
    <div class="profile container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
                    <!-- Top Profile (with banner) -->
            <div class="userProfileTop">
                <div class="bannerMain clearfix" style="background: url(<?php echo url('/'); ?>/user/<?php echo $profile->unique_salt_id; ?>/banner_picture);">
                    <div class="cover clearfix">
                        <div class="mainProfileHold clearfix col-lg-9">
                            <div class="profileImage">
                                <div class="mainPPImage" style="background-image: url(<?php echo url('/'); ?>/user/<?php echo $profile->unique_salt_id; ?>/profile_picture);"></div>
                            </div>
                            <div class="rightInfo">
                                <div class="topMain">
                                    <h3><?php echo $profile->name; ?></h3>
                                    <h4>@<?php echo $profile->username; ?></h4>
                                </div>
                                <div class="middleDesc">
                                    <p><?php echo $info->user_bio; ?></p>
                                </div>
                                <div class="bottomMiddleStats">
                                    <dl>
                                        <a class="profileLink" href="/p/<?php echo $profile->username; ?>">
                                            <dt><?php echo count($poems); ?></dt>

                                            <?php if(count($poems) == 1){ ?>
                                            <dd><span class="UserStatsCountLabel">Poem</span></dd>
                                            <?php } else { ?>
                                            <dd><span class="UserStatsCountLabel">Poems</span></dd>
                                            <?php } ?>
                                        </a>
                                    </dl>
                                    <dl>
                                        <a class="profileLink" href="/p/<?php echo $profile->username; ?>">
                                            <dt><?php echo count($posts); ?></dt>

                                            <?php if(count($posts) == 1){ ?>
                                            <dd><span class="UserStatsCountLabel">Post</span></dd>
                                            <?php } else { ?>
                                            <dd><span class="UserStatsCountLabel">Posts</span></dd>
                                            <?php } ?>
                                        </a>
                                    </dl>
                                    <dl>
                                        <a class="profileLink" href="/p/<?php echo $profile->username; ?>">
                                            <dt><?php echo count($followers); ?></dt>

                                            <?php if(count($followers) == 1){ ?>
                                            <dd><span class="UserStatsCountLabel">Follower</span></dd>
                                            <?php } else { ?>
                                            <dd><span class="UserStatsCountLabel">Followers</span></dd>
                                            <?php } ?>
                                        </a>
                                    </dl>
                                    <dl>
                                        <a class="profileLink" href="/p/<?php echo $profile->username; ?>">
                                            <dt><?php echo count($followings); ?></dt>

                                            <?php if(count($followings) == 1){ ?>
                                            <dd><span class="UserStatsCountLabel">Following</span></dd>
                                            <?php } else { ?>
                                            <dd><span class="UserStatsCountLabel">Followings</span></dd>
                                            <?php } ?>
                                        </a>
                                    </dl>
                                </div>
                                <div class="bottomProfile">
                                    <?php if(Auth::check() && auth()->user()->unique_salt_id != $profile->unique_salt_id){ ?>
                                    <?php if(count($follow->check($profile->unique_salt_id)) == 0){ ?>
                                    <a href="{{ route('follow.subscribe') }}" data-token="{{ csrf_token() }}" data-id="<?php echo $profile->unique_salt_id; ?>" class="followBtn btn btn-success">Follow</a>
                                    <?php } else { ?>
                                    <a href="{{ route('follow.unsubscribe') }}" data-token="{{ csrf_token() }}" data-id="<?php echo $profile->unique_salt_id; ?>" class="unfollowBtn btn btn-danger">Unfollow</a>
                                    <?php } ?>
                                    <a class="btn btn-primary" href="<?php echo url('/'); ?>/incog/<?php echo $profile->username; ?>">Send Anon</a>
                                    <?php } else { ?>
                                    <?php if(Auth::check()){ ?>
                                    <a class="btn btn-primary" href="{{ route('settings.index') }}">Settings</a>
                                    <?php } ?>
                                    <?php } ?>

                                    <?php if(!Auth::check()){ ?>
                                    <a class="btn btn-primary" href="{{ route('login') }}">Login to follow <?php echo $user[0]->name; ?></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom profile -->
            <div class="bottomContainer">
                <div class="profileNav">
                    <div class="profileNavInner">
                        <uL>
                            <li><a href="/p/<?php echo $user[0]->username; ?>">Poems</a></li>
                            <li><a href="/p/<?php echo $user[0]->username; ?>/feed">Feed</a></li>
                            <li><a href="/p/<?php echo $user[0]->username; ?>/followings">Followings</a></li>
                            <li class="active"><a href="/p/<?php echo $user[0]->username; ?>/followers">Followers</a></li>
                            <li><a href="/p/<?php echo $user[0]->username; ?>/about">About</a></li>
                        </uL>
                    </div>
                </div>
                <div class="followFeedHold full">
                    <?php
                    if(count($followings) > 0)
                    {
                    foreach($followings as $followee)
                    {
                    // Get user info
                    $user = DB::table('users')->where('unique_salt_id', $followee->followee_id)->get()[0];
                    ?>
                    <div class="followBox" onClick="window.location.assign('/p/<?php echo $user->username; ?>');">
                        <div class="profilePic">
                            <div class="profilePicImage" style="background-image: url(<?php echo url('/'); ?>/user/<?php echo $user->unique_salt_id; ?>/profile_picture);"></div>
                        </div>
                        <div class="bottomProfile">
                            <h3><?php echo $user->name; ?></h3>
                            <h4>@<?php echo $user->username; ?></h4>
                        </div>
                    </div>
                    <?php
                    }
                    }
                    ?>
                </div>
            </div>
    </div>
@endsection