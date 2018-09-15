<?php
$stylesheet = "conversations";
?>

@extends('layouts.logged-in-main')

@section('content')
    <div class="conversationsContainer container">
        <div class="conversationsBanner">
            <div class="cover">
                <div class="innerBanner">
                    <h3>Conversations</h3>
                </div>
            </div>
        </div>
        <div class="conversationsContent">
            <div class="col-lg-3 leftConversations">
                <div class="innerContent">
                    <div class="topContent">
                        <h4>Threads</h4>
                    </div>
                    <div class="bottomThread">

                    </div>
                </div>
            </div>
            <div class="col-lg-9 rightConversations">
                <div class="innerContent">

                </div>
            </div>
        </div>
    </div>
@endsection