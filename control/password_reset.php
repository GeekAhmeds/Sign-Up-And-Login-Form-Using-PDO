<?php
/*
1- password reset.html {
    -form action(password_reset.php)
    -email Address , submit
}
2- password reset.php {
    - check email exist or not ,
    - token , hash , hash,
    - link(password recovery.php)
    -email()
}
3- password recovery.php {
    - check email, token,
    - (email, token) => select database,
    - UPDATE password , (email, token),
    - delete token
}
*/
require_once '../inc/connection.php';
if (isset($_POST['email'], $_POST['submit']) && !empty($_POST['email'])) {
  if (filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email =:email');
    $stmt->execute([
      'email' => $_POST['email']
    ]);
    if ($stmt->rowCount()) {
      //update token , generate link
      $stmt = $pdo->prepare('UPDATE users SET reset_token =:reset_token WHERE email =:email');
      $stmt->execute([
        'email' => $_POST['email'],
        'reset_token' => sha1(uniqid('', true)) . sha1(date('Y-m-d H:i'))
      ]);
      if ($stmt->rowCount()) {
        $stmt = $pdo->prepare('SELECT email,reset_token FROM users WHERE email =:email');
        $stmt->execute([
          'email' => $_POST['email']
        ]);
        if ($stmt->rowCount()) {
          foreach ($stmt->fetchAll() as $value) {
            //If Yo Can Send Email Do It Here
            ?>
            <a href="password_recovery.php?email=<?= $value['email']; ?>&reset_token=<?= $value['reset_token']; ?>">
            Click Here To Reset Password</a>
            <?php

          }
        }
      }
    }else {
      echo "Email Not Found !!!";
    }
  }else {
    echo "Please Enter A Valid Email";
  }
}else {
  echo "Please Fill Up Input";
}
