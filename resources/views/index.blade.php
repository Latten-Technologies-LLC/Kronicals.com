<?php
$stylesheet = "index";

?>
@extends('layouts.logged-out-main')

@section('content')
<div class="full-height row">
    <!-- Left section -->
    <div class="full-height-container col-4 hidden-md-down left-preview">
        <div class="inner-cont">

        </div>
    </div>
    <!-- Right section -->
    <div class="full-height-container col-md-8 col-sm-12 col-xs-12 main-message">
        <div class="topBanner">
            <div class="cover"></div>

        </div>
        <div class="topMainMessage">
            <div class="topBrand">
                <div class="topWelcome">
                    <h3 class="defaultFontColor">Welcome to</h3>
                </div>
                <div class="bottomName">
                    <h3 class="primaryFontColor"><?php echo env("APP_NAME"); ?></h3>
                </div>
            </div>
            <div class="middleArea">
                <div class="topMessage">
                    <p><?php echo config('app.short_description'); ?></p>
                </div>
                <div class="divider"></div>
                <div class="bottomLoginArea">
                    <div class="innerForm">
                        <h4>Login</h4>
                        <form class="form-horizontal" autocomplete="off" method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <div class="col-md-6">
                                    <input id="email" autocomplete="off" placeholder="Email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <div class="col-md-6 input-field">
                                    <input id="password" placeholder="Password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <span>Remember Me</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        Login
                                    </button>

                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        Forgot Your Password?
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottomArea">
            <h3 class="defaultFontColor">Copyright &copy; 20<?php echo date('y'); ?></h3>
            <h4 class="defaultFontColor">Site built and designed by <a class="hrefPrimaryColor" href="https://sitelyftstudios.com">Sitelyft Studios</a></h4>
        </div>
    </div>
</div>
@endsection