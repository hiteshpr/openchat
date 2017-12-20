<?php

require (dirname(__DIR__).'/vendor/autoload.php');
use ChatApp\LoginSocial;

if (isset($_POST['q'])) {

    $authField = json_decode($_POST['q']);
   
     $name = $authField->name;
     $email = $authField->email;
     $id = $authField->id;
     $authFlag= $authField->social_auth;

    $obSocialLogin = new LoginSocial();
    
    $data = array(
        'id' => $id,
         'name' => $name,
         'email' => $email,
         'authFlag' =>$authFlag
     );
    $result = $obSocialLogin->authSocialLogin($data);

     if (isset($result)) {
         echo $result;
     } else {
         echo json_encode([]);
     }
}