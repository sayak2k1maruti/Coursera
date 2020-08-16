<?php
    $salt = "XyZzy12*_";
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=Registry', 'AutoGrader', 'auto1234');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $userDetails = null;
    session_start();
    session_destroy();
    session_start();
    if (isset($_POST['cancel'])) {
        header("location:index.php");
        return;
    }
    if (isset($_POST['email']) && isset($_POST['pass'])) {
        $sql = $pdo->prepare("SELECT user_id,name,email FROM `users` WHERE email = :e AND password = :p");
        $sql ->execute(array(
                            ':e' => $_POST['email'],
                            ':p' => hash("md5",$salt.$_POST['pass'])
                            )
                            );
        $userDetails = $sql -> fetch(PDO::FETCH_ASSOC);
        if($userDetails == null){
            $_SESSION['error'] = "Incorrect password and email id";
        }else{
            $_SESSION['user_id'] = $userDetails['user_id'];
            $_SESSION['name'] = $userDetails['name'];
            header("location:index.php");
            return;
        }
    }

?>
<!DOCTYPE html>
<html>
<head>
<title>Sayak Das's Login Page</title>
<link rel="icon"  href="../icon.jpg">
<!-- bootstrap.php - this is HTML -->

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
    crossorigin="anonymous">
<!-- Optional theme -->
<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
    crossorigin="anonymous">

</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
    if( isset($_SESSION['error']) ){
        echo("<p style=\"color: red;\">".$_SESSION['error']."</p>");
        unset($_SESSION['error']);
    }
?>
<form method="POST" action="login.php">
<label for="email">Email</label>
<input type="text" name="email" id="email"><br/>
<label for="id_1723">Password</label>
<input type="password" name="pass" id="id_1723"><br/>
<input type="submit" onclick="return doValidate();" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<script>
function doValidate() {
    console.log('Validating...');
    try {
        addr = document.getElementById('email').value;
        pw = document.getElementById('id_1723').value;
        console.log("Validating addr="+addr+" pw="+pw);
        if (addr == null || addr == "" || pw == null || pw == "") {
            alert("Both fields must be filled out");
            return false;
        }
        if ( addr.indexOf('@') == -1 ) {
            alert("Invalid email address");
            return false;
        }
        return true;
    } catch(e) {
        return false;
    }
    return false;
}
</script>
</div>
<pre>
    <?php
    print_r($userDetails);
    ?>
</pre>
</body>
