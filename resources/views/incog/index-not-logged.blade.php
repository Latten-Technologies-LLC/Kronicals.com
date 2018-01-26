<?php
$stylesheet = "incog";

// Get user
$user = new \App\Libraries\User();

?>
@extends('layouts.logged-out-main')

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
    <!-- Top Profile (with banner) -->

    <!-- Main system -->
    @include('incog.templates.base');
@endsection