<?php
    $salt = 'XyZzy12*_';
    $stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';
    $who="";
    $pass="";
    $failure = false;
    if(isset($_POST["cancel"])){
        header("location:index.php");
    }
    if(isset($_POST['who']) && isset($_POST['pass'])){
        $who = $_POST['who'];
        $psss = $_POST['pass'];
        if(strlen($_POST['who'])>0 && strlen($_POST['pass'])>0){
            if(strpos($_POST['who'],'@')){
                $checkpass = $salt.$_POST['pass'];
                $md5 = hash('md5',$checkpass);
                if($stored_hash == $md5){
                    error_log("Login success ".$_POST['who']);
                    $url = "autos.php?name=".$_POST["who"];
                    header("location:$url");
                }else{
                    $failure = "Incorrect password";
                    error_log("Login fail ".$_POST['who']." $md5");
                }
            }else{
                $failure = "Email must have an at-sign (@)";
            }
        }else{
            $failure = "Email and password are required";
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
    if($failure != false){
        echo("<p style=\"color: red;\">".$failure."</p>");
        
    }
?>
<form method="POST">
<label for="nam">User Name(Ur Email id)</label>
<input type="text" name="who" id="nam"><br/>
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