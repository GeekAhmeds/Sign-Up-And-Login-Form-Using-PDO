<?php
session_start();
require_once '../inc/connection.php';
// email, password
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  if (isset($_POST['email'], $_POST['password']) && !empty($_POST['email']) && !empty($_POST['password']) ) {
    # code...
    if (strlen($_POST['password']) >= 8 && strlen($_POST['password']) <= 32) {
      if (filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username =:username');
        $stmt->execute([
          'username' => $_SESSION['username']
        ]);
        if ($stmt->rowCount()) {
          foreach ($stmt->fetchAll() as $value) {
            if (password_verify($_POST['password'],$value['password'])) {
              $stmt = $pdo->prepare('SELECT * FROM users WHERE email =:email ');
              $stmt->execute([
                'email' => $_POST['email']
              ]);
              if ($stmt->rowCount()) {
                echo "Email Is Already Taken Pick Up Another One";
              }else {


              $stmt = $pdo->prepare('UPDATE users SET email =:email WHERE username =:username AND id =:id');
              $stmt->execute([
                'email'     =>  $_POST['email'],
                'username'  =>  $_SESSION['username'],
                'id'        =>  $_SESSION['id']
              ]);
              if ($stmt->rowCount()) {
                echo "Email Has Been Changed";
              }
            }
            }else {
              echo "Password incorrect";
            }
          }
        }else {
          echo "string";
        }
      }else {
        echo "Please A Provide Us A Valid Email";
      }
    }else {
      echo "Your Password Is Weak";
    }
  }else {
    echo "Please Fill Up Your Form !";
  }

}else {
  die('You Have To Login');
}
