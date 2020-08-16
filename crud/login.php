<?php
  session_start();
  $salt = 'XyZzy12*_';
  $stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';
  if(isset($_SESSION['user'])){
    unset($_SESSION['user']);
  }
  if(isset($_POST['email']) && isset($_POST['pass'])){
    $who = $_POST['email'];
    $psss = $_POST['pass'];
    if(strlen($_POST['email'])>0 && strlen($_POST['pass'])>0){
      $checkpass = $salt.$_POST['pass'];
      $md5 = hash('md5',$checkpass);
      if($stored_hash == $md5){
        error_log("Login success ".$_POST['email']);
        // Redirect the browser to view.php
        $_SESSION['user'] = $_POST['email'];
        header("Location: index.php");
        return;
      }else{
        $_SESSION['error'] = "Incorrect password";
        header("Location: login.php");
        return;
        error_log("Login fail ".$_POST['email']." $md5");
        }
      }else{  
        $_SESSION['error'] = "User name and password are required";
        header("Location: login.php");
        return;
      }  
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Sayak Das's Login Page</title>
<link rel = "icon" href = "icon.jpg">
<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
    crossorigin="anonymous">

<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
    crossorigin="anonymous">

<link rel="stylesheet" 
    href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>

<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
  crossorigin="anonymous"></script>

</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
  if(isset($_SESSION['error'])){
    echo("<p style=\"color: red;\">".htmlentities($_SESSION['error'])."</p>");
    unset($_SESSION['error']);
  }
?>
<form method="POST" action="login.php">
<label for="nam">User Name</label>
<input type="text" name="email" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="password" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
<a href="index.php">Cancel</a></p>
</form>
<p>
Password Hint: he password is the three character name of the
programming language used to write serverside code (all lower case)
followed by 123.
</p>
</div>
</body>