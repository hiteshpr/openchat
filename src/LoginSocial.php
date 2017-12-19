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
     * To Authenticate User Credentials
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

       if($authFlag == 'fb_auth'){
    
              $query = "INSERT INTO register VALUES(null, '$email', null, null,null,'$id')";
        
                }elseif ($authFlag == 'g_auth') {
        
            $query = "INSERT INTO register VALUES(null, '$email', null, null,'$id',null)";           
                }
        $this->connect->query($query);

        $query = "SELECT id FROM register WHERE email = '$email'";

        if ($result = $this->connect->query($query)) {
            $row = $result->fetch_assoc();
            $userId = $row['id'];
            $query = "INSERT INTO login VALUES 
            ('$userId', '$name', '$email', null, null, 0)";
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

       if($authFlag == 'fb_auth'){
    
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
        }

    }
    }
            
}

   


