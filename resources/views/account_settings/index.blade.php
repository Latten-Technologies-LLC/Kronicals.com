
<?php
$stylesheet = "settings";

// Get user
$user = new \App\Libraries\User();

$user_table = json_decode($user->get(auth()->user()->id), true);
$user_info_table = json_decode($user->get(auth()->user()->id, '', 'user_info'), true);
?>
@extends('layouts.logged-in-main')

@section('content')
<br /><br /><br /><br />

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('settings.change_basic_info') }}" method="post" id="change_basic_info">
    <input type="text" name="name" placeholder="Name" value="<?php echo $user_table['user']['name']; ?>"/>
    <input type="text" name="interests" placeholder="Interests" value=""/>
    <textarea name="bio" placeholder="Bio" value=""></textarea>
    {{ csrf_field() }}
    <input type="submit" class="btn btn-primary" value="Update" />
</form>
<form action="{{ route('settings.change_email') }}" method="post" id="change_basic_info">
    <input type="text" name="email" placeholder="Email" value="<?php echo $user_table['user']['email']; ?>"/>
    {{ csrf_field() }}
    <input type="submit" class="btn btn-primary" value="Update" />
</form>
<form action="{{ route('settings.change_password') }}" method="post" id="change_password">
    <input type="password" name="current_password" placeholder="Current Password">
    <input type="password" name="new_password" placeholder="New Password">
    <input type="password" name="new_password_confirmation" placeholder="Confirm Password">
    {{ csrf_field() }}
    <input type="submit" class="btn btn-primary" value="Update" />
</form>
<form action="{{ route('settings.change_profile_picture') }}" enctype="multipart/form-data" method="post" id="change_password">
    <img src="<?php echo url('/'); ?>/user/<?php echo auth()->user()->unique_salt_id; ?>/profile_picture" />
    <input type="file" name="profile_picture" />
    {{ csrf_field() }}
    <input type="submit" class="btn btn-primary" value="Update" />
</form>
<form action="{{ route('settings.change_profile_banner') }}" enctype="multipart/form-data" method="post" id="change_password">
    <img src="<?php echo url('/'); ?>/user/<?php echo auth()->user()->unique_salt_id; ?>/profile_banner" />
    <input type="file" name="profile_banner" />
    {{ csrf_field() }}
    <input type="submit" class="btn btn-primary" value="Update" />
</form>
@endsection