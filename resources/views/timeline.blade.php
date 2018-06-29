<?php
$stylesheet = "timeline";

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

// Tutorials
use App\Libraries\TutorialSystem;

$tutorials = TutorialSystem::parse('timeline', 'feed');
$validate = TutorialSystem::validate($tutorials);
?>
@extends('layouts.logged-in-main')

@section('content')
    <?php
    if($validate > 0)
    {
        // Show
        TutorialSystem::display($tutorials, $validate, 'timeline', 'feed');
    }
    ?>
    <div class="container timelineMiddle">
        <div class="inner row">
            <!-- Left main feed -->
            <div class="leftFeed col-xl-9 col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="card profileLink d-block d-sm-none" style="display: none;margin-bottom: 10px;">
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
                    <div class="topPostingStation">
                        <div class="innerPostingStation">
                            <form action="{{ route('posting.new') }}" method="post" enctype="multipart/form-data" id="postingStation">
                                <div class="topArea">
                                    <div class="innerArea clearfix">
                                        <div class="profilePicLeft">
                                            <div class="pp" style="background-image: url(<?php echo url('/'); ?>/user/<?php echo auth()->user()->unique_salt_id; ?>/profile_picture);"></div>
                                        </div>
                                        <div class="userInfo">
                                            <h3><?php echo ucwords(auth()->user()->name); ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="middleArea">
                                    <div class="innerMiddleArea">
                                        <div class="normal_entry input-group">
                                            <textarea class="input-field" name="Textfield" id="postingStationText" placeholder="Write something meaningful"></textarea>
                                        </div>
                                        <div class="hidden diary_entry">
                                            <div class="diary_title">
                                                <input type="text" id="diary_title_input" name="diary_title" placeholder="Title" />
                                            </div>
                                            <div class="main_diary_entry"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bottomArea hidden clearfix">
                                    <div class="leftPostTypeArea">
                                        <div class="privacyList pull-right">
                                            <div id="dd" class="wrapper-dropdown-1" tabindex="1">
                                                <div class="wrapper-drop-head openPrivTab">
                                                    <span class="currentSetting" style="color: #333;font-weight: 400;"><i class="fas fa-newspaper"></i> Post</span> <span class="priv-carrot"><i class="fa fa-caret-down"></i></span>
                                                </div>
                                                <ul class="dropdown hidden privacyDrop">
                                                    <li class="privTabSetting privActive" data-priv="1" data-val="<i class='fas fa-newspaper'></i> Post">
                                                        <h3><i class="fas fa-newspaper"></i> Post</h3>
                                                        <div>Everyone can see this</div>
                                                    </li>
                                                    <li class="privTabSetting" data-priv="2" data-val="<i class='fas fa-align-left'></i> Thoughts">
                                                        <h3><i class="fas fa-align-left"></i> Poem</h3>
                                                        <div>When you're feeling a little poetic</div>
                                                    </li>
                                                    <li class="privTabSetting" data-priv="3" data-val="<i class='fas fa-book'></i> Diary">
                                                        <h3><i class="fas fa-book"></i> Diary</h3>
                                                        <div>This will be added to your personal diary. Only you can see it!</div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rightAction">
                                        <input type="submit" class="btn btn-success" value="Post" />
                                    </div>
                                </div>
                                <input type="hidden" name="Privacy" id="def-privacy" value="1" />
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                    <div class="bottomTimelineFeed">
                        <?php
                            if(count($feed) > 0)
                            {
                                foreach(json_decode($feed) as $post)
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
                                                    <?php if($post->type != 3){ ?>
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
                            <b><h5>Price: $2.95</h5></b>
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

    @section('stylesheets')
        <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <link href="//cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet">
    @endsection

    @section('scripts')
        <script src="https://js.braintreegateway.com/js/braintree-2.32.1.min.js"></script>
        {{--<script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
        <script src="{{ asset('js/quill/imageresize.js') }}"></script>--}}
        <script type="text/javascript">
            $(document).ready(function(){

            });

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