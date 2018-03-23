<?php
namespace App\Libraries;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Libraries\User;
use App\Libraries\Notifications;

class TutorialSystem
{
    /*
     * For new users. Creates an array of initial tutorial details
     */
    public static function init()
    {
        return [
            'timeline' => array(
                'feed' => array(
                    'timeline' => array('name' => 'Timeline', 'desc' => 'Welcome to your timeline, this is like your home! It will showcase what your friends are up to and its also where you can check your diary and anonymous messages!', 'read' => 0),
                    'posting_station' => array('name' => 'Posting Area', 'desc' => 'The posting station gives you your voice. It allows you to post things to your timeline and for your followers to see. Also you can post thoughts and diary entries from there as well!','read' => 0),
                    'sidebar_area' => array('name' => 'Sidebar', 'desc' => 'This is where you will find your link to share with your friends, your profile information and also the latest users on the site!', 'read' => 0)
                ),
                'anon' => array('name' => 'Anons', 'desc' => 'This is where you can view your anonymous messages from people!', 'read' => 0),
                'sent' => array('name' => 'Sent anons', 'desc' => 'This is where you can view your sent messages', 'read' => 0)
            ),
            'profile',
            'incog' => array(
                'incog' => array('name' => 'Sending Anons', 'desc' => 'This is where you can send messages to your friends. You have the option to make them anonymous by default!', 'read' => 0),
                'confessing' => array('name' => 'Confessing', 'desc' => 'You can also confess to your anonymous messages from your timeline!', 'read' => 0)
            ),
        ];
    }

    /*
     * Parses the logged users tutorial stats
     */
    public static function parse($page, $sub, $deep_sub = "")
    {
        if(!empty($page) && !empty($sub))
        {
            // Get logged users tutorial
            $tutorial = json_decode(auth()->user()->tutorial);

            // Parse it
            if($deep_sub == "")
            {
                return $tutorial->$page->$sub;
            }else{
                return $tutorial->$page->$sub->$deep_sub;
            }
        }
    }

    /*
     * Will check to see if any needs displaying
     */
    public static function validate($tutorial, $single = false)
    {
        if(!empty($tutorial))
        {
            $count = 0;

            if($single == false)
            {
                // Iterate
                foreach ($tutorial as $tut) {
                    if ($tut->read == 0) {
                        $count++;
                    }
                }
            }else{
                // Count
                if($tutorial->read == 0)
                {
                    $count++;
                }
            }
        }
        
        return $count;
    }

    /*
     * This will display the tutorials that aren't read yet
     */
    public static function display($tutorials, $pages, $main, $sub = "")
    {
        // If something is there
        if($pages > 0)
        {
            // Start HTML
            ?>
                <!-- Tutorial Overlay -->
                <div class="tutorial_overlay"></div>

                <!-- Lock screen -->
                <style>
                    html, body{
                        overflow: hidden !important;
                    }
                </style>

                <!-- Main tutorial -->
                <div class="tutorial_main_hold">
                    <div class="inner_tutorial_hold">
                    <?php
                        if($pages > 1)
                        {
                            // Iterate
                            $i = 1;

                            foreach ($tutorials as $tutorial)
                            {
                                if ($tutorial->read == 0)
                                {
                                    ?>
                                    <div class="tutorial_page <?php if ($i > 1) { ?> hidden <?php } ?>" id="tutorial_page<?php echo $i; ?>">
                                        <div class="inner_page">
                                            <div class="top_page">
                                                <h2>Tutorial</h2>
                                                <h3><?php echo $tutorial->name; ?></h3>
                                            </div>
                                            <div class="middle_page">
                                                <p><?php echo $tutorial->desc; ?></p>
                                            </div>
                                            <div class="bottom_page">
                                                <h4>Page <?php echo $i; ?> of <?php echo $pages; ?></h4>
                                                <?php
                                                if ($i < $pages) {
                                                    ?>
                                                    <button class="btn tutorial_skip_btn" data-token="" data-tut="<?php echo $main; ?><?php if ($sub != "") { ?>|<?php echo $sub; ?><?php } ?>">Skip</button>
                                                    <button class="btn tutorial_next_btn" data-current="<?php echo $i; ?>" data-next="<?php echo $i + 1; ?>">Next</button>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <button class="btn tutorial_skip_btn" data-token="" data-tut="<?php echo $main; ?><?php if ($sub != "") { ?>|<?php echo $sub; ?><?php } ?>">Skip</button>
                                                    <button class="btn tutorial_finished_btn" data-token="" data-tut="<?php echo $main; ?><?php if ($sub != "") { ?>|<?php echo $sub; ?><?php } ?>" data-current="<?php echo $i; ?>">Finished</button>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }

                                // Add
                                $i++;
                            }
                        }else{

                            ?>
                            <div class="tutorial_page <?php if ($pages > 1) { ?> hidden <?php } ?>" id="tutorial_page<?php echo $pages; ?>">
                                <div class="inner_page">
                                    <div class="top_page">
                                        <h2>Tutorial</h2>
                                        <h3><?php echo $tutorials->name; ?></h3>
                                    </div>
                                    <div class="middle_page">
                                        <p><?php echo $tutorials->desc; ?></p>
                                    </div>
                                    <div class="bottom_page">
                                        <h4>Page <?php echo $pages; ?> of <?php echo $pages; ?></h4>
                                        <?php
                                        if ($pages < $pages) {?>
                                            <button class="btn tutorial_skip_btn" data-token="" data-tut="<?php echo $main; ?><?php if ($sub != "") { ?>|<?php echo $sub; ?><?php } ?>">Skip</button>
                                            <button class="btn tutorial_next_btn" data-current="<?php echo $pages; ?>" data-next="<?php echo $pages + 1; ?>">Next</button>
                                            <?php
                                        } else {
                                            ?>
                                            <button class="btn tutorial_skip_btn" data-token="" data-tut="<?php echo $main; ?><?php if ($sub != "") { ?>|<?php echo $sub; ?><?php } ?>">Skip</button>
                                            <button class="btn tutorial_finished_btn" data-token="" data-tut="<?php echo $main; ?><?php if ($sub != "") { ?>|<?php echo $sub; ?><?php } ?>" data-current="<?php echo $pages; ?>">Finished</button>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                    </div>
                </div>
            <?php
        }
    }

    /*
     * This will update the persons stuff
     */
    public static function update($data)
    {
        if(!empty($data))
        {
            // Explode
            $tutorial_data = explode('|', $data);

            // Get logged users tutorials
            $tutorials = json_decode(auth()->user()->tutorial, true);

            // Count
            $count = count($tutorials[$tutorial_data[0]][$tutorial_data[1]]) - 1;
            $keys = array_keys($tutorials[$tutorial_data[0]][$tutorial_data[1]]);

            // Now lets see what we got
            if($count > 1 && is_array($tutorials[$tutorial_data[0]][$tutorial_data[1]][$keys[0]]))
            {
                for ($i = 0; $i <= $count; $i++)
                {
                    $tutorials[$tutorial_data[0]][$tutorial_data[1]][$keys[$i]]['read'] = 1;
                }
            }else{
                //print_r($tutorials[$tutorial_data[0]][$tutorial_data[1]][2]);
                $tutorials[$tutorial_data[0]][$tutorial_data[1]]['read'] = 1;
            }

            // Update in database
            DB::table('users')->where('unique_salt_id', auth()->user()->unique_salt_id)->update(['tutorial' => json_encode($tutorials)]);
            
            // Return
            echo json_encode(['code' => 1, 'message' => 'Tutorial updated']);
        }
    }
}