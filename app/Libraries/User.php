<?php
namespace App\Libraries;
use Illuminate\Support\Facades\DB;

/*
 * User
 * ---
 * Desc: This will hold convenient functions to help us access specific data about
 * a specific user
 */
class User
{
    /*
     * Will be used to hold a persons
     * user ID globally
     */
    protected $user_id;

    /*
     * This will hold the response from the functions
     */
    protected $response = array();

    /*
     * Get()
     * ---
     * Desc: This will return all the data of the user from the "Users" table.
     * Or you can find something specific
     */
    public function get($id, $specific = "", $table = "users")
    {
        if(!empty($id))
        {
            // Create object
            $user = DB::table($table)->where('id', $id)->get();

            if(count($user) == 1)
            {
                // Return object
                if($specific == "")
                {
                    $this->response = array(
                        'code' => 1,
                        'desc' => 'We were able to find a user',
                        'user' => $user[0]
                    );
                    return json_encode($this->response);
                }else{
                    $this->response = array(
                        'code' => 1,
                        'desc' => 'We were able to find a user',
                        $specific => $user[0]->$specific
                    );
                    return json_encode($this->response);
                }
            }else{
                $this->response = array(
                    'code' => 0,
                    'desc' => 'This user couldn\'t be found'
                );

                return json_encode($this->response);
            }
        }
    }
    
    /*
     * exists()
     * ---
     * This will quickly tell us if the user exists or not
     */
    static public function exists($id)
    {
        if(!empty($id))
        {
            return DB::table('users')->where('id', $id)->get();
        }
    }
}