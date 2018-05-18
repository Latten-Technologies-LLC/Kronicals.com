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

// Entries
use \App\Libraries\DiarySystem;
$entries = DiarySystem::fetchDiary(auth()->user()->unique_salt_id);
?>
@extends('layouts.logged-in-main')

@section('content')
    <div class="diaryContainer container">
        <div class="diaryBanner">
            <div class="cover">
                <div class="innerBanner">
                    <h3>Diary</h3>
                </div>
            </div>
        </div>
        <div class="diaryContent">
            <div class="topEntries">
                <h3><?php if( count($entries) == 1) { ?>1 entry <?php } else { ?> <?php echo count($entries); ?> entries<?php } ?></h3>
            </div>
            <div class="innerContent">
                <?php
                if(count($entries) > 0)
                {
                    ?>
                    <div class="columnList">
                        <?php
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
                                        <p><?php echo $text; ?></p>
                                    </div>
                                    <div class="bottomMainInfo">
                                        <p><?php echo ucwords(auth()->user()->name); ?> &middot; <?php echo time_elapsed_string($entry->entry_date); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <?php
                }else {
                    ?>
                        <div class="alert-message">
                            <div class="innerAlert">
                                <h3>Looks like you have no entries yet!</h3>
                            </div>
                        </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
@endsection