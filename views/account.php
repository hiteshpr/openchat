<?php

require_once (dirname(__DIR__).'/vendor/autoload.php');
use ChatApp\User;
use ChatApp\Profile;
use ChatApp\Session;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();
// die("Hello");

$user = explode("/", $_SERVER['REQUEST_URI']);
$user = $user[count($user) - 1];
$userId = Session::get('start');
if ($userId != null && $user == "account.php") {
    $obUser = new User();
    $row = $obUser->userDetails($userId, True);

    if ($row != NULL) {
        $location = getenv('APP_URL')."/views/account.php/".$row['username'];
        header("Location:".$location);
    }
} elseif ($user != "account.php") {
    $obUser = new User();
    $row = $obUser->userDetails($user, False);
    if ($row != NULL) {
        $userId = $row['login_id'];
        $details = Profile::getProfile($userId);
        if ($details != NULL) {
            $row = array_merge($row, $details);
        } else {
            header("Location:".getenv('APP_URL')."/views/error.php");
        }
?>

        <!Doctype html>
        <html>
            <head>
                <title>OpenChat || Profile</title>
                <link rel="stylesheet" type="text/css" href="../../public/assests/css/bootstrap.min.css">
                <link rel="stylesheet" href="../../public/assests/css/profile.css">
            </head>

            <body>

                <nav class="navbar navbar-inverse">
                    <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="">OpenChat</a>
                    </div>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="../../index.php">About</a></li>
                    <?php if (Session::get('start') != null) { ?>
                        <li><a href="../message.php">Message</a></li>
                        <li><a href="../logout.php">Log out</a></li>
                    <?php } else { ?>
                        <li><a href="../login.php">Login</a></li>
                        <li><a href="../register.php">Register</a></li>
                    <?php } ?>
                    </ul>
                    </div>
                </nav>

                <div class="row">
                     <div class="container"  >
                        <div class="col-sm-4 col-xs-12">
                           <img src="../../public/assests/img/ankit.png" class="img-responsive img-circle center-block profilepic img-thumbnail">
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-sm-8 col-xs-12">
                           
                                <div class="detailsinfo">
                                  
                                  
                                      <h1 id="name"><b>Name: <?php echo $row['name']; ?></b></h1><br>
                                    <?php foreach ($row as $key => $value) {
                                        if ($key == 'username' && $value != null) {
                                            echo '<p>Username: '.$row["username"].'</p><br>';
                                        }
                                        if ($key == 'email' && $value != null) {
                                            echo '<p>Email ID: '.$row["email"].'</p><br>';
                                        }
                                        if ($key == 'status' && $value != null) {
                                            echo '<p>Status: '.$row["status"].'</p><br>';
                                        }
                                        if ($key == 'education' && $value != null) {
                                            echo '<p>Education: '.$row["education"].'</p><br>';
                                        }
                                        if ($key == 'gender' && $value != null) {
                                            echo '<p>Gender:     '.$row["gender"].'</p><br>';
                                        }
                                    }
                                    ?>
                                   
                                    <div class="clearfix"></div>
                                    <div>
                                         <?php if (Session::get('start') == $row['login_id']) { ?>
                                    <button class="btn btn-primary" href="#" data-toggle="modal" data-target="#myModal">Edit Profile</button>
                                <?php } ?>
                                    </div>
                                
                                </div>
                             
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
               
                    

                    <?php
                        if (Session::get('start') == $row['login_id']) {
                    ?>


                      <!-- Modal -->
                      <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog">
                        
                          <!-- Modal content-->
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">Edit details</h4>
                            </div>
                            <div class="modal-body">

                        <form method="post" action="../profile_generate.php">
                            <div class="form-group">
                                <label>Status : </label>
                                <textarea name="status" class="form-control" id="status"><?php echo $row['status']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Education : </label>
                                 <input type="text" class="form-control" name="education" id="education" value="<?php echo $row['education']; ?>"></input>
                            </div>
                            <div class="form-group">
                                <label>Gender : </label><br>
                                <input type="radio"  name="gender" id="gender" value="Male" <?php echo ($row['gender'] == 'Male') ? 'checked' : '' ?>> Male<br>
                            </div>
                                <div class="form-group">
                                <input type="radio" name="gender" id="gender" value="Female" <?php echo ($row['gender'] == 'Female') ? 'checked' : '' ?>> Female<br>
                            </div>
                                <input type="submit" class="btn btn-default" name="submit" value="Done" id="submit">
                        </form>
                            </div>
                          </div>
                          
                        </div>
                      </div>

        
                    <?php } ?>
               


                <div class="footer ">
                        <div class="container">
                          

                          
                        <div class="col-sm-12">
                          <div class="footertext">
                             <h3 class="footer_text">Made with love by <a href="#">Ankit Jain</a></h3>
                             
                          </div>
                          </div>
                          
                          


                        </div>
                </div>

               

                 <script type="text/javascript" src="../../public/assests/js/jquery-3.0.0.min.js"></script>
                <script type="text/javascript" src="../../public/assests/js/bootstrap.min.js"></script>
                <script type="text/javascript" src="../../public/assests/js/profile.js"></script>
                <script type="text/javascript" src="../../node_modules/place-holder.js/place-holder.min.js"></script>
            </body>
        </html>
<?php
    } else {
        header("Location:".getenv('APP_URL')."/views/error.php");
    }
} else {
    header("Location: ".getenv('APP_URL')."/views/");
}
?>

