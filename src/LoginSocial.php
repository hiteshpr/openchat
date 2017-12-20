<?php
/**
 * LoginSocial Class Doc Comment
 *
 * PHP version 5
 *
 * @category PHP
 * @package  OpenChat
 * @author   Ankit Jain <ankitjain28may77@gmail.com>
 * @license  The MIT License (MIT)
 * @link     https://github.com/ankitjain28may/openchat
 */
namespace ChatApp;

require_once dirname(__DIR__).'/vendor/autoload.php';
use ChatApp\Session;
use mysqli;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();

/**
 * To Login the User
 *
 * @category PHP
 * @package  OpenChat
 * @author   Ankit Jain <ankitjain28may77@gmail.com>
 * @license  The MIT License (MIT)
 * @link     https://github.com/ankitjain28may/openchat
 */
class LoginSocial
{
    /*
    |--------------------------------------------------------------------------
    | Login Class
    |--------------------------------------------------------------------------
    |
    | To Login the User.
    |
    */

    protected $flag;
    protected $error;
    protected $connect;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->flag = 0;
        $this->connect = new mysqli(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );
        $this->error = array();
    }

    /**
     * To Authenticate User Social Credentials
     *
     * @param array $data To store User Credentials
     *
     * @return string
     */
    public function authSocialLogin($data)
    {
       
       

        $id = $data["id"];
        $name = $data["name"];
        $email = $data["email"];
        $authFlag =$data["authFlag"];

       /*If API data has email ID field*/
       if($email != null){

        if($authFlag == 'fb_auth'){
            $query = "SELECT * FROM register WHERE email='$email' and fb_id='$id'";
        }
        elseif ($authFlag == 'g_auth') {
          $query = "SELECT * FROM register WHERE email='$email' and g_id='$id'";
        }

        /*If records found, log in */
            
         if ($result = $this->connect->query($query)) {

            if ($result->num_rows > 0) {

                 $row = $result->fetch_assoc();
                  
                  $loginID = $row['id'];

                  Session::put('start', $loginID);
                            return json_encode(
                                [
                                "location" => getenv('APP_URL')."/views/account.php"
                                ]
                            );
            }

       }

       /* If email found update the google id or facebook id field and login*/

       $query= "SELECT * FROM register WHERE email='$email'";
       if ($result = $this->connect->query($query)) {
             if ($result->num_rows > 0) {

                $row = $result->fetch_assoc();

                    $loginID = $row['id'];

                if($authFlag == 'fb_auth'){
    
             $query = "UPDATE register SET fb_id = '$id' WHERE email='$email'";
        
                }elseif ($authFlag == 'g_auth') {
        
            $query = "UPDATE register SET g_id = '$id' WHERE email='$email'";           
                }

                if ($result = $this->connect->query($query)) {

                     Session::put('start', $loginID);
                            return json_encode(
                                [
                                "location" => getenv('APP_URL')."/views/account.php"
                                ]
                            );                    
                }


             }

       }

       /* If no records of email found register the user in database and login in. */

       if($authFlag == 'fb_auth'){
    
              $query = "INSERT INTO register VALUES(null, '$email', '$email', null,null,'$id')";
        
                }elseif ($authFlag == 'g_auth') {
        
            $query = "INSERT INTO register VALUES(null, '$email', '$email', null,'$id',null)";           
                }
        $this->connect->query($query);

        $query = "SELECT id FROM register WHERE email = '$email'";

        if ($result = $this->connect->query($query)) {
            $row = $result->fetch_assoc();
            $userId = $row['id'];
            $query = "INSERT INTO login VALUES 
            ('$userId', '$name', '$email', '$email', null, 0)";
            $this->connect->query($query);

            $query = "INSERT INTO profile VALUES(
                '$userId', 'Joined OpenChat', 'Joined OpenChat', '')";

            $this->connect->query($query);

            Session::put('start', $userId);
            return json_encode(
                [
                "location" => getenv('APP_URL')."/views/account.php"
                ]
            );
        }

    } else{
        /**
          * If email is not received in API.
          * Check if any account with same fb id or google id is present.
          * If present, login.
          *
          * 
        */

        if($authFlag == 'fb_auth'){
            $query = "SELECT * FROM register WHERE fb_id='$id'";
        }
        elseif ($authFlag == 'g_auth') {
          $query = "SELECT * FROM register WHERE g_id='$id'";
        }

        if ($result = $this->connect->query($query)) {

            if ($result->num_rows > 0) {

                 $row = $result->fetch_assoc();
                  
                  $loginID = $row['id'];

                  Session::put('start', $loginID);
                            return json_encode(
                                [
                                "location" => getenv('APP_URL')."/views/account.php"
                                ]
                            );
            }

       }
        
        /* If no account present, throw error because email or username is necessary for further functionalities of the app like account and message section.*/

       return json_encode(
                ["Error" => "Email not found! Please make your email ID public."]
            );

      /* if($authFlag == 'fb_auth'){
    
              $query = "INSERT INTO register VALUES(null, null, null, null,null,'$id')";
        
                }elseif ($authFlag == 'g_auth') {
        
            $query = "INSERT INTO register VALUES(null, null, null, null,'$id',null)";           
                }
        $this->connect->query($query);

        
        if($authFlag == 'fb_auth'){
    
             $query = "SELECT id FROM register WHERE fb_id = '$id'";
        
                }elseif ($authFlag == 'g_auth') {
        
           $query = "SELECT id FROM register WHERE g_id = '$id'";           
                }


        if ($result = $this->connect->query($query)) {
            $row = $result->fetch_assoc();
            $userId = $row['id'];
            $query = "INSERT INTO login VALUES 
            ('$userId', '$name', null, null, null, 0)";
            $this->connect->query($query);

            $query = "INSERT INTO profile VALUES(
                '$userId', 'Joined OpenChat', 'Joined OpenChat', '')";

            $this->connect->query($query);

            Session::put('start', $userId);
            return json_encode(
                [
                "location" => getenv('APP_URL')."/views/account.php"
                ]
            );
        }*/

      }
    }
            
}

   


