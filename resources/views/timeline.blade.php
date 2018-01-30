<?php
$stylesheet = "timeline";
?>
@extends('layouts.logged-in-main')

@section('content')
    <div class="container timelineMiddle">
        <div class="inner row">
            <!-- Left main feed -->
            <div class="leftFeed col-8">
                <div class="top">
                    <h3>Messages</h3>
                </div>
                <div class="bottom">
                    <?php
                        // Loop through all the messages
                        foreach(json_decode($messages, true) as $message)
                        {
                            ?>
                                <div class="message">
                                    <div class="innerMessage">

                                    </div>
                                </div>
                            <?php
                        }
                    ?>
                </div>
            </div>

            <!-- Right sidebar -->
            <div class="rightSidebar col-4">
                <div class="card userMod">

                </div><br />
                <div class="card notifications">

                </div><br />
                <div class="card ads">
                    
                </div>
                <div class="no_footer">
                    <ul>
                        <li><a href="<?php //echo route(''); ?>">About</a></li>
                        <li><a href="<?php //echo route(''); ?>">Settings</a></li>
                        <li><a href="<?php //echo route(''); ?>">Logout</a></li>
                    </ul>
                    <span>&copy; 2018 Annonuss &middot; Built by <a href="https://sitelyftstudios.com/">Sitelyft Studios</a></span>
                </div>
            </div>
        </div>
    </div>
@endsection