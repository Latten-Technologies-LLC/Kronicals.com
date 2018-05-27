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
$entry = DB::table('timeline_posts')->where(['user_id' => auth()->user()->unique_salt_id, 'type' => '3', 'id' => $diary_entry[0]->parent_id])->get();

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
                <div class="leftDiaryEntry col-lg-8 col-md-8">
                    <div class="innerLeftDiaryEntry">
                        <div class="topEntry">
                            <p>
                                <?php echo \Illuminate\Support\Facades\Crypt::decrypt($entry[0]->text); ?>
                            </p>
                        </div>
                        <div class="bottomEntry">
                            <h5><?php echo ucwords(auth()->user()->name); ?> &middot; <?php echo time_elapsed_string($entry[0]->date); ?></h5>
                        </div>
                    </div>
                </div>
                <div class="rightDiaryEntry col-lg-4 col-md-4">
                    <div class="innerRightDiaryEntry">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection