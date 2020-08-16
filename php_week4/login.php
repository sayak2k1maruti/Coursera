<?php
    $salt = 'XyZzy12*_';
    $stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';
    $who="";
    $pass="";
    if(isset($_POST["cancel"])){
        header("location:index.php");
    }
    session_start();
    if(isset($_POST['email']) && isset($_POST['pass'])){
        $who = $_POST['email'];
        $psss = $_POST['pass'];
        if(strlen($_POST['email'])>0 && strlen($_POST['pass'])>0){
            if(strpos($_POST['email'],'@')){
                $checkpass = $salt.$_POST['pass'];
                $md5 = hash('md5',$checkpass);
                if($stored_hash == $md5){
                    error_log("Login success ".$_POST['email']);
                    // Redirect the browser to view.php
                    $_SESSION['name'] = $_POST['email'];
                    header("Location: view.php");
                    return;
                }else{
                    $_SESSION['error'] = "Incorrect password";
                    header("Location: login.php");
                    return;
                    error_log("Login fail ".$_POST['email']." $md5");
                }
            }else{
                $_SESSION['error'] = "Email must have an at-sign (@)";
                header("Location: login.php");
                return;
            }
        }else{
            $_SESSION['error'] = "Email and password are required";
            header("Location: login.php");
            return;
        }  
    }
?>


<!DOCTYPE html>
<html>
<head>
<link rel = 'icon' href = "icon.jpg">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<title>Sayak Das's Login Page</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
    if ( isset($_SESSION['error']) ){
        echo("<p style=\"color: red;\">".htmlentities($_SESSION['error'])."</p>");
        unset($_SESSION['error']);
    }
?>
<form method="POST">
<label for="nam">Email</label>
<input type="text" name="email" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="password" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
    Password Hint: The password is the three character name of the 
programming language used in backend development (all lower case) 
followed by 123
</p>
</div>
</body>
</html>