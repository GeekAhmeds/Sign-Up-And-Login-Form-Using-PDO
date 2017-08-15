<?php
session_start();
require_once '../inc/connection.php';
// email, password
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  if (isset($_POST['nickname'],$_POST['submit'], $_POST['password']) && !empty($_POST['nickname']) && !empty($_POST['password']) ) {
    if (preg_match('/^[a-z0-9 ]*$/i', $_POST['nickname'])) {
    if (strlen($_POST['password']) >= 8 && strlen($_POST['password']) <= 32) {
      $stmt = $pdo->prepare('SELECT * FROM users WHERE email =:email');
      $stmt->execute([
        'email' => $_SESSION['email']
      ]);
      if ($stmt->rowCount()) {
        foreach ($stmt->fetchAll() as $value) {
          if (password_verify($_POST['password'], $value['password'])) {
            $stmt =$pdo->prepare('UPDATE users SET nickname =:nickname WHERE email =:email');
            $stmt->execute([
              'nickname' => $_POST['nickname'],
              'email' => $_SESSION['email']
            ]);
            if ($stmt->rowCount()) {
              echo "Hello " . $_POST['nickname'];
            }else {
              echo "Error In Statement";
            }
          }else {
            echo "Incorrect Password";
          }
        }
      }else {
        echo "Somthing Error !!!";
      }
      }else {
      die("Please Provide Us A Valid password");
      }
    }else {
      echo "Please Type A Valid nickname";
     }
  }else {
    echo "Please Fill Up Inputs";
  }
}else {
  die('You Have To Login !!!');
}
