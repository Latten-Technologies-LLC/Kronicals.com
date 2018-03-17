<?php
$stylesheet = "profile";

use Illuminate\Support\Facades\DB;

use App\Libraries\User;
use App\Libraries\FollowSystem;

// Follow system
$follow = new FollowSystem();

// Find user
$exists = User::exists($user[0]->id);

// Check
if(count($exists) == 1)
{
    // Then we good
    $profile = $user[0];
    $info = DB::table('user_info')->where('unique_salt_id', $profile->unique_salt_id)->get()[0];
}else{
    redirect('/');
}
?>
@extends('layouts.logged-in-main')

@section('content')
    <br /><br /><br /><br />

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
            <div class="cover">
                <div class="bannerMain" style="background: url(<?php echo url('/'); ?>/user/<?php echo $profile->unique_salt_id; ?>/banner_picture);"></div>
                <div class="mainProfileHold">
                    <div class="profileImage">
                        <img src="<?php echo url('/'); ?>/user/<?php echo $profile->unique_salt_id; ?>/profile_picture" />
                    </div>
                    <div class="rightInfo">
                        <h3><?php echo $profile->name; ?></h3>
                        <h4>@<?php echo $profile->username; ?></h4>
                        <p><?php echo $info->user_bio; ?></p>
                        <?php if(auth()->user()->unique_salt_id != $profile->unique_salt_id){ ?>
                            <?php if(count($follow->check($profile->unique_salt_id)) == 0){ ?>
                                <a href="{{ route('follow.subscribe') }}" data-token="{{ csrf_token() }}" data-id="<?php echo $profile->unique_salt_id; ?>" class="followBtn btn btn-primary">Follow</a>
                            <?php } else { ?>
                                <a href="{{ route('follow.unsubscribe') }}" data-token="{{ csrf_token() }}" data-id="<?php echo $profile->unique_salt_id; ?>" class="unfollowBtn btn btn-danger">Unfollow</a>
                            <?php } ?>
                            <a class="btn btn-success" href="<?php echo url('/'); ?>/incog/<?php echo $profile->username; ?>">Send Anon</a>
                        <?php } else { ?>
                            <a class="btn btn-success" href="{{ route('settings.index') }}">Settings</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom profile -->
        <div class="bottomContainer">
            
        </div>
    </div>
@endsection