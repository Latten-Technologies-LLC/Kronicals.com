
<?php
$stylesheet = "settings";

// Get user
$user = new \App\Libraries\User();

$user_table = json_decode($user->get(auth()->user()->id), true);
$user_info_table = json_decode($user->get(auth()->user()->id, '', 'user_info'), true);
?>
@extends('layouts.logged-in-main')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container settings">
    <div class="innerContainer">
        <h1>Settings</h1>
        <div class="firstUserInfo">
            <div class="topName">
                <h3>User Info</h3>
            </div>
            <div class="bottomContent">
                <form action="{{ route('settings.change_basic_info') }}" method="post" id="change_basic_info">
                    <div class="form-group">
                        <input type="text" class="form-control" name="name" placeholder="Name" value="<?php echo $user_table['user']['name']; ?>"/>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="interests" placeholder="Interests" value=""/>
                    </div>
                    <div class="form-group">
                        <textarea name="bio" class="form-control" placeholder="Bio" value=""></textarea>
                    </div>
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-primary" value="Update" />
                </form>
            </div>
        </div>
        <div class="secondUserInfo">
            <div class="topName">
                <h3>Change email</h3>
            </div>
            <div class="bottomContent">
                <form action="{{ route('settings.change_email') }}" method="post" id="change_basic_info">
                    <div class="form-group">
                        <input type="text" class="form-control" name="email" placeholder="Email" value="<?php echo $user_table['user']['email']; ?>"/>
                    </div>
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-primary" value="Update" />
                </form>
            </div>
        </div>
        <div class="thirdUserInfo">
            <div class="topName">
                <h3>Change password</h3>
            </div>
            <div class="bottomContent">
                <form action="{{ route('settings.change_password') }}" method="post" id="change_password">
                    <div class="form-group">
                        <input type="password" class="form-control" name="current_password" placeholder="Current Password">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="new_password" placeholder="New Password">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="new_password_confirmation" placeholder="Confirm Password">
                    </div>
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-primary" value="Update" />
                </form>
            </div>
        </div>
        <div class="fourthUserInfo">
            <div class="topName">
                <h3>Change profile password</h3>
            </div>
            <div class="bottomContent">
                <form action="{{ route('settings.change_profile_picture') }}" enctype="multipart/form-data" method="post" id="change_password">
                    <img src="<?php echo url('/'); ?>/user/<?php echo auth()->user()->unique_salt_id; ?>/profile_picture" />
                    <div class="form-group">
                        <input type="file" name="profile_picture" />
                     </div>
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-primary" value="Update" />
                </form>
            </div>
        </div>
        <div class="fifthUserInfo">
            <div class="topName">
                <h3>Change banner</h3>
            </div>
            <div class="bottomContent">
                <form action="{{ route('settings.change_profile_banner') }}" enctype="multipart/form-data" method="post" id="change_password">
                    <img src="<?php echo url('/'); ?>/user/<?php echo auth()->user()->unique_salt_id; ?>/profile_banner" />
                    <div class="form-group">
                        <input type="file" name="profile_banner" />
                    </div>
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-primary" value="Update" />
                </form>
            </div>
        </div>
    </div>
</div>

@endsection