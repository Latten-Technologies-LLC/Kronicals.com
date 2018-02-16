<?php
$stylesheet = "profile";

use Illuminate\Support\Facades\DB;
use App\Libraries\User;

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
                            <a class="btn btn-success" href="<?php echo url('/'); ?>/incog/<?php echo $profile->username; ?>">Send Anon</a>
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection