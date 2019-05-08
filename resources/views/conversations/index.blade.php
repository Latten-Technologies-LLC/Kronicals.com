<?php
$stylesheet = "conversations";

// Conversaitons system
use App\Libraries\ConversationsSystem;
$c = new ConversationsSystem();
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
            <div class="col-lg-4 leftConversations">
                <div class="innerContent">
                    <div class="topContent">
                        <h4>Threads</h4>
                    </div>
                    <div class="bottomThread">
                        <?php 
                        foreach($c->gatherThreads(Auth::user()->unique_salt_id) as $con)
                        {
                            // Conversation
                            $conversation = $c->gatherThread($con->conversation_id, false);

                            // See if its a group
                            ?>
                                <div class="box">
                                    <div class="box-left">
                                        <?php
                                            if($conversation['conversation'][0]->conversation_type == "private")
                                            {
                                                ?>
                                                    <div class="profile-pic" style="height: 50px;width: 50px;background-size: cover;background-image: url(<?php echo url('/'); ?>/user/<?php echo $conversation['members'][0]->user_id ?>/profile_picture);"></div>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                    <div class="box-right">
                                    
                                    </div>
                                </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 rightConversations">
                <div class="innerContent">

                </div>
            </div>
        </div>
    </div>
@endsection