<?php
namespace App\Libraries;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Libraries\User;

class ConversationsSystem
{
    private $db;
    private $response;

    /**
     * Construct function
     */
    public function __construct()
    {
        $this->db = new DB;
    }

    /**
     * gatherThreads
     * ---
     * This will gather a users threads
     * 
     * @var $id
     * @var $limit (optional) (Default: 5)
     */
    public function gatherThreads($id, int $limit = 5)
    {
        if(!empty($id))
        {
            return $this->db::table('conversation_members')->where('user_id', $id)->get();
        }
    }

    /**
     * gatherMembers
     * ---
     * This will gather the members of a specific group
     * 
     * @var $id (Conversation ID)
     * @var $limit (Optional) (Default: 5)
     */
    public function gatherMembers($id, int $limit = 5, $includeSelf = true)
    {
        if($this->checkConversationExists($id))
        {
            if($includeSelf == true)
            {
                return $this->db::table('conversation_members')->where('conversation_id', $id)->get();
            }else{
                return $this->db::table('conversation_members')->where([['conversation_id', '=', $id], ['user_id', '!=', '' . Auth::user()->unique_salt_id . '']])->get();
            }
        }else {
            return false;
        }
    }

     /**
     * gatherMessages
     * ---
     * Gather the messages for a conversation
     * 
     * @var $id (Conversation ID)
     * @var $limit (Optional) (Default: 0)
     */
    public function gatherMessages($id)
    {
        if($this->checkConversationExists($id))
        {
            return $this->db::table('conversation_messages')->where('conversation_id', $id)->get();
        }else{
            return false;
        }
    }

    /**
     * gatherThread
     * ---
     * This will gather a thread and all its information including members and messages
     * 
     * @var $id (Conversation ID)
     * @var $includeSelf (Bool)
     */
    public function gatherThread($id, $includeSelf = true)
    {
        if($this->checkConversationExists($id))
        {
            // Create array
            $conversation['conversation'] = $this->db::table('conversations')->where('conversation_id', $id)->get();
            $conversation['members'] = $this->gatherMembers($id, 0, $includeSelf);
            $conversation['messages'] = $this->gatherMessages($id, 0);

            return $conversation;
        }else{
            return false;
        }
    }

    /**
     * checkConversationExists
     * ---
     * This will check to see if a conversation exists
     * 
     * @var $id (Conversation ID)
     */
    public function checkConversationExists($id)
    {
        if(!empty($id))
        {
            // DB Query 
            $check = $this->db::table('conversations')->where('conversation_id', $id)->get();

            if(count($check) == 1)
            {
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * betweenTwo
     * ---
     * This will check to see if a conversation exists between two parties
     * 
     * @var $uid1 (User One)
     * @var $uid2 (User Two)
     */
    public function betweenTwo($uid1, $uid2)
    {

    }

    /**
     * addToConversation
     * ---
     * This will add a new member to the conversation
     * 
     * @var $id (Conversation ID)
     * @var $uid (User ID)
     */
    public function addToConversation($id, $uid)
    {

    }

    /**
     * leaveConversation
     * ---
     * This will allow a user to leave a conversation
     * 
     * @var $id (Conversation ID)
     * @var $uid (User ID)
     */
    public function leaveConversation($id, $uid)
    {

    }

    /**
     * createMessage
     * ---
     * This will allow a user to create a message
     * 
     * @var $id (Conversation ID)
     * @var 
     */
    public function createMessage()
    {

    }
}