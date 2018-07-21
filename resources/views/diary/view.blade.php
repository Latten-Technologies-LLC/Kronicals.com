<?php
$stylesheet = "diary";

// Funciton
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'h',
        'i' => 'm',
        's' => 's',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

// Entry
$diary_entry = DB::table('diary_entry')->where(['id' => $entry_id])->get();
$entry = DB::table('timeline_posts')->where(['user_id' => auth()->user()->unique_salt_id, 'id' => $diary_entry[0]->parent_id])->get();

if(count($entry) < 1)
{
    header('location: /diary');
}
?>
@extends('layouts.logged-in-main')

@section('content')
    <div class="diaryContainer container">
        <div class="diaryBanner diary_view">
            <div class="cover">
                <div class="innerBanner">
                    <h3>Diary</h3>
                    <h4><?php echo $diary_entry[0]->entry_title; ?></h4>
                </div>
            </div>
        </div>
        <div class="diaryContent">
            <div class="innerContent">
                <div class="leftDiaryEntry col-lg-8 col-md-8 col-sm-8 col-xs-12">
                    <div class="innerLeftDiaryEntry">
                        <div class="topEntry">
                            <h3><?php echo $diary_entry[0]->entry_title; ?></h3>
                            <p>
                                <?php echo \Illuminate\Support\Facades\Crypt::decrypt($entry[0]->text); ?>
                            </p>
                        </div>
                        <div class="bottomEntry">
                            <h5><?php echo ucwords(auth()->user()->name); ?> &middot; <?php echo time_elapsed_string($entry[0]->date); ?></h5>
                        </div>
                    </div>
                </div>
                <div class="rightDiaryEntry col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="innerRightDiaryEntry">
                        <div class="box user_info">
                            <div class="profilePic">
                                <div class="pp" style="background-image: url(<?php echo url('/'); ?>/user/<?php echo auth()->user()->unique_salt_id; ?>/profile_picture);"></div>
                            </div>
                            <div class="userInfo">
                                <h3><a href="/p/<?php echo auth()->user()->username; ?>"><?php echo auth()->user()->name; ?></a></h3>
                            </div>
                        </div>
                        <div class="box actions clearfix">
                            <h3>Actions</h3>
                            <div class="leftItems col-lg-12">
                                <button data-action="{{ route('diary.convert') }}" data-type="convert" data-token="{{ csrf_token() }}" data-pid="<?php echo $diary_entry[0]->id; ?>" class="col-lg-6 diaryActions convert btn btn-success">Poem</button>
                                <button title="Coming soon" disabled class="col-lg-6 edit btn btn-disabled">Edit</button>
                            </div><br /><br />
                            <div class="secondary">
                                <button data-token="{{ csrf_token() }}" data-action="{{ route('posting.action.delete') }}" data-type="delete" data-pid="<?php echo $diary_entry[0]->parent_id; ?>" class="diaryActions delete btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </div>
                        <div class="box latest_entries">
                            <h3>Latest entries</h3>
                            <div class="listing">
                                <?php
                                    use \App\Libraries\DiarySystem;
                                    $entries = DB::table('diary_entry')->where(['entry_author' => auth()->user()->unique_salt_id])->where('id', '!=', $diary_entry[0]->id)->get();

                                    if(count($entries) > 0)
                                    {
                                        foreach($entries as $entry)
                                        {
                                        $text = \Illuminate\Support\Facades\Crypt::decrypt($entry->entry_text);

                                        if(strlen($text) > 275)
                                        {
                                            // Truncate string
                                            $stringCut = substr($text, 0, 275);
                                            $endPoint = strrpos($stringCut, ' ');

                                            //if the string doesn't contain any space then it will cut without word basis.
                                            $text = $endPoint? substr($stringCut, 0, $endPoint):substr($stringCut, 0);
                                            $text .= '... <br /><a href="/diary/view/' . $entry->id . '">Read More</a>';
                                        }
                                        ?>
                                        <div class="entry" onClick="window.location.assign('<?php echo url('/'); ?>/diary/view/<?php echo $entry->id; ?>');">
                                            <div class="innerEntry">
                                                <div class="topMainText">
                                                    <h4><strong style="font-weight: 400;"><?php echo $entry->entry_title; ?></strong></h4>
                                                    <p><?php echo $text; ?></p>
                                                </div>
                                                <div class="bottomMainInfo">
                                                    <p><?php echo ucwords(auth()->user()->name); ?> &middot; <?php echo time_elapsed_string($entry->entry_date); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        }
                                    }else{
                                        ?>
                                        <H5 style="text-align: left;color: #aaa;font-size: 1em;">No other entries to show</H5>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection