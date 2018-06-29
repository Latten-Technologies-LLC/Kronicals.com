<?php
$stylesheet = "index";
?>
@extends('layouts.logged-out-main')

@section('content')
    <style>
        .website, .topBanner{
            background-image: url(https://images.unsplash.com/photo-1495572050486-a9b739c11fb9?ixlib=rb-0.3.5&ixid=eyJhcHBfaWQiOjEyMDd9&s=7ed8945979820790676347b7c6b75174&auto=format&fit=crop&w=1534&q=80) !important;
        }
    </style>
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
                        <h3 class="defaultFontColor">Learn more</h3>
                    </div>
                    <div class="bottomName">
                        <h3 class="primaryFontColor">About Us</h3>
                    </div>
                </div>
                <div class="middleArea">
                    <div class="topMessage">
                        <p>This page should tell you a little bit about us and what we're creating here</p>
                    </div>
                    <div class="divider"></div><br />
                    <div class="box">
                        <h3>The purpose</h3>
                        <p>We want to give aspiring poets a platform that allows them to have a voice and to be able to show the world who they are and what they can bring to the table</p>
                    </div>
                    <div class="box">
                        <h3>Messaging</h3>
                        <p>We've enabled people to have a way to message their favorite poets or their friends in an anonymous way to share valuable feedback to one another</p>
                    </div>
                    <div class="box">
                        <h3>Security</h3>
                        <p>Every message is thoroughly encrypted and secure so you wont have to worry about anyone hacking in and viewing your stuff</p>
                    </div>
                    <div class="box">
                        <h3>Confessing</h3>
                        <p>Primarily we give you the option to send messages anonymously, that way you can say how you feel without all of the awkwardness. But we also give you an option to confess and actually show the person on the other side who you really are.</p>
                    </div>
                    <div class="box">
                        <h3>Our future</h3>
                        <p>We plan on adding a lot more features to this platform. Feeds, sending pictures, group messages, more profile options and more. But right now we want to focus on the main purposes!</p>
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