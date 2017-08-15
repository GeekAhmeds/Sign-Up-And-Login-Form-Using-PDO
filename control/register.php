<?php
require_once '../inc/connection.php';

if (isset($_POST['username'],$_POST['password'],$_POST['email'],$_POST['password_confirm'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $password_confirm = $_POST['password_confirm'];
  $email = $_POST['email'];
  if (preg_match('/^[a-z0-9-_. ]*$/i', $username)) {
    if (strlen($password) >= 8 && strlen($password) <= 32) {
      if ($password_confirm === $password) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
          $stmt->execute([
            $username
          ]);
          if ($stmt->rowCount()) {
            die('Username Is Already Taken, Please Pick Up Another One');
          }else {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->execute([
              $email
            ]);
              if ($stmt->rowCount()) {
                die('Email Is Already Taken, Please Pick Up Another One');
              }else {
                $stmt = $pdo->prepare('INSERT INTO users (`username`,`password`,`email`) VALUES (?,?,?)');
                $stmt->execute([
                  $username,
                  password_hash($password,PASSWORD_DEFAULT,['cost' => 11]),
                  $email
                ]);
                if ($stmt->rowCount()) {
                  echo "Thanks For Register, Please Go To Email Account For Confirm Register";
                }
              }
              }
        } else {
          echo 'Please Provide A Valid Email';
        }

      }else {
        echo 'Password Confirm Doesn\'t Match';
      }
    }else {
      echo 'Please Provide A Valid Password';
    }
  }else {
    echo 'Please Provide A Valid Username';
  }
}
