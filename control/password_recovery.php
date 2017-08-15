<?php
require_once '../inc/connection.php';
if (isset($_GET['email'], $_GET['reset_token']) && !empty($_GET['email']) && !empty($_GET['reset_token'])) {
   // check email and token
    if (filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
         $stmt = $pdo->prepare('SELECT * FROM users WHERE email =:email AND reset_token =:reset_token');
         $stmt->execute([
           'email'        =>  $_GET['email'],
           'reset_token'  =>  $_GET['reset_token']
         ]);
         if ($stmt->rowCount()) {
           ?>




           <head>
             <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
             <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

           </head>
           <form class="form-horizontal" action="" method="POST">
           <fieldset>
           <legend></legend

           <!-- Password input-->
           <div class="form-group">
             <label class="col-md-4 control-label" for="new_password">New Password</label>
             <div class="col-md-5">
               <input id="new_password" name="new_password" type="password" placeholder="Enter Your New Password" class="form-control input-md" required="">

             </div>
           </div>

           <!-- Password input-->
           <div class="form-group">
             <label class="col-md-4 control-label" for="c_password">Repeat Password</label>
             <div class="col-md-5">
               <input id="c_password" name="c_password" type="password" placeholder="Repeat Password" class="form-control input-md" required="">

             </div>
           </div>
           <!-- Button -->
           <div class="form-group">
             <label class="col-md-4 control-label" for="submit"></label>
             <div class="col-md-4">
               <button id="submit" name="submit" class="btn btn-default">Reset Password</button>
             </div>
           </div>

           </fieldset>
           </form>









           <?php
           if (isset($_POST['new_password'], $_POST['c_password']) && !empty($_POST['new_password']) && !empty($_POST['c_password'])) {
               if (strlen($_POST['new_password']) >= 8 && strlen($_POST['new_password']) <= 32) {
                 if ($_POST['c_password'] === $_POST['new_password']) {
                    $stmt = $pdo->prepare('UPDATE users SET password =:password WHERE reset_token =:reset_token AND email =:email');
                    $stmt->execute([
                      'password'        =>  password_hash($_POST['new_password'],PASSWORD_DEFAULT,['cost' => 11]),
                      'reset_token'     =>  $_GET['reset_token'],
                      'email'           =>  $_GET['email']
                    ]);
                    if ($stmt->rowCount()) {
                      $stmt = $pdo->prepare('UPDATE users SET reset_token =:reset_token WHERE email =:email');
                      $stmt->execute([
                        'reset_token' =>  NULL,
                        'email'       =>  $_GET['email']
                      ]);
                        if ($stmt->rowCount()) {
                          echo "Password Has Been Changed";
                        }else {
                          echo "Wrong In Statement";
                        }
                    }else {
                      echo "Something Error !";
                    }
                  }else {
                    echo "Password Confirm Don\'t Match";
                  }
                }else {
                  echo "Please Provide A Valid Password";
                }
              }else {
                echo "Please Fill Up Inputs";
              }
         }else {
           die('invalid token!!!');
         }
    }else {
      echo "Sorry can\'t Find Email !";
    }

}else {
  echo "You Shouldn\'t Visit This Page";
}
