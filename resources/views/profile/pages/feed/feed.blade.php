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
                            <li class="active"><a href="/p/<?php echo $user[0]->username; ?>/feed">Feed</a></li>
                            <li><a href="/p/<?php echo $user[0]->username; ?>/followings">Followings</a></li>
                            <li><a href="/p/<?php echo $user[0]->username; ?>/followers">Followers</a></li>
                            <li><a href="/p/<?php echo $user[0]->username; ?>/about">About</a></li>
                        </uL>
                    </div>
                </div>

                <div class="poemFeedHold">
                    <?php
                    if(count($posts) > 0)
                    {
                    foreach($posts as $post)
                    {
                    // User info
                    $user = DB::table('users')->where('unique_salt_id', $post->user_id)->get()[0];
                    ?>
                    <div id="post<?php echo $post->id; ?>" class="post <?php if($post->type == '2'){ ?>thought<?php }else if($post->type == '3'){ ?>diary <?php } ?>">
                        <div class="topPost clearfix">
                            <div class="innerTopPost">
                                <div class="left">
                                    <div class="pp" style="background-image: url(<?php echo url('/'); ?>/user/<?php echo $user->unique_salt_id; ?>/profile_picture);"></div>
                                </div>
                                <div class="right">
                                    <h3><a href="<?php echo url('/'); ?>/p/<?php echo $user->username; ?>"><?php echo ucwords($user->name); ?></a></h3>
                                    <?php if($post->type == '2'){ ?>
                                    <h4 class="subType">Created a poem &middot; <?php echo time_elapsed_string($post->date); ?></h4>
                                    <?php }else if($post->type == '3'){ ?>
                                    <h4 class="subType"><i class="fas fa-book"></i> Private diary &middot; <?php echo time_elapsed_string($post->date); ?> &middot;
                                        <?php if(auth()->user()->unique_salt_id == $post->user_id){ ?>
                                        <li style="display: inline;" data-token="{{ csrf_token() }}" data-type="delete" data-pid="<?php echo $post->id; ?>" class="postAction"><a style="color: #acacad" href="{{ route('posting.action.delete') }}"><i class="fas fa-trash-alt"></i></a></li>
                                        <?php } ?>
                                    </h4>
                                    <?php } else { ?>
                                    <h4 class="subType">Shared a post &middot; <?php echo time_elapsed_string($post->date); ?></h4>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="middlePost">
                            <div class="innerMiddlePost">
                                <div class="topMiddle">
                                    <p><?php echo Crypt::decrypt($post->text); ?></p>
                                </div>
                                <div class="bottomMiddle">
                                    <?php if($post->type != 3 && Auth::check()){ ?>
                                    <ul>
                                        <span class="likeHolder">
                                            <?php if(count($postingsystem->check($post->id)) == 0){ ?>
                                            <li data-token="{{ csrf_token() }}" data-type="like" data-pid="<?php echo $post->id; ?>" class="postAction"><a href="{{ route('posting.action.like') }}"><span class="count"><?php echo $postingsystem->count($post->id); ?></span> <i class="far fa-heart"></i> Like</a></li>
                                                <li data-token="{{ csrf_token() }}" data-type="unlike" data-pid="<?php echo $post->id; ?>" class="postAction unlike hidden"><a href="{{ route('posting.action.unlike') }}"><span class="count"><?php echo $postingsystem->count($post->id); ?></span> <i class="fas fa-heart"></i> Unlike</a></li>
                                            <?php } else { ?>
                                            <li data-token="{{ csrf_token() }}" data-type="like" data-pid="<?php echo $post->id; ?>" class="postAction hidden"><a href="{{ route('posting.action.like') }}"><span class="count"><?php echo $postingsystem->count($post->id); ?></span> <i class="far fa-heart"></i> Like</a></li>
                                                <li data-token="{{ csrf_token() }}" data-type="unlike" data-pid="<?php echo $post->id; ?>" class="postAction unlike"><a href="{{ route('posting.action.unlike') }}"><span class="count"><?php echo $postingsystem->count($post->id); ?></span> <i class="fas fa-heart"></i> Unlike</a></li>
                                            <?php } ?>
                                        </span>
                                        <li data-token="{{ csrf_token() }}" data-type="reply" data-pid="<?php echo $post->id; ?>" class="postAction"><a href="">Reply</a></li>
                                        <?php if(auth()->user()->unique_salt_id == $post->user_id){ ?>
                                        <li data-token="{{ csrf_token() }}" data-type="delete" data-pid="<?php echo $post->id; ?>" class="postAction" style="float: right"><a href="{{ route('posting.action.delete') }}"><i class="fas fa-trash-alt"></i></a></li>
                                        <?php } ?>
                                    </ul>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php if($post->type != 3){ ?>
                        <div class="bottomPost hidden" id="postReplyBox<?php echo $post->id; ?>">
                            <div class="innerBottomPost">
                                <div class="commentsHold" id="postReplyBoxHold<?php echo $post->id; ?>">
                                    <div class="row">
                                        <?php
                                        $replies = $postingsystem->replies($post->id);

                                        // Change stuff
                                        foreach($replies as $reply)
                                        {
                                        // Get user info
                                        $user = DB::table('users')->where('unique_salt_id', $reply->user_id)->get();
                                        ?>
                                        <div class="reply">
                                            <div class="leftProfile">
                                                <div class="innerPP" style="background-image: url(<?php echo url('/'); ?>/user/<?php echo $user[0]->unique_salt_id; ?>/profile_picture);"></div>
                                            </div>
                                            <div class="rightProfile">
                                                <h3><a href="<?php echo url('/'); ?>/p/<?php echo $user[0]->username; ?>"><?php echo ucwords($user[0]->name); ?></a> &middot; <span><?php echo time_elapsed_string($reply->date); ?></span></h3>
                                                <p><?php echo Crypt::decrypt($reply->message); ?></p>
                                            </div>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php if(Auth::check()){ ?>
                                <div class="commentMaker">
                                    <form action="{{ route('posting.action.comment') }}" method="post" class="postReplyMakerForm" data-id="<?php echo $post->id; ?>">
                                        <div class="row">
                                            <div class="pp">
                                                <div class="mainpphold" style="background-image: url(<?php echo url('/'); ?>/user/<?php echo auth()->user()->unique_salt_id; ?>/profile_picture);"></div>
                                            </div>
                                            <div class="input">
                                                <input type="text" class="replyInput" data-id="<?php echo $post->id; ?>" id="postReplyInput<?php echo $post->id; ?>" placeholder="Press enter or return to comment" />
                                                <input type="hidden" class="replyId" id="pid" value="<?php echo $post->id; ?>" />
                                                <input type="hidden" class="replyToken" value="{{ csrf_token() }}" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <?php
                    }
                    } else {
                    ?>
                    <style>
                        .bottomTimelineFeed{
                            column-count: 1 !important;
                        }
                    </style>
                    <div class="message error">
                        <h1 style="text-align: center;color: #aaa;"><i class="far fa-frown"></i></h1>
                        <h4 style="text-align: center;">Your timeline is empty. Post something now or follow someone!</h4>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
    </div>
@endsection