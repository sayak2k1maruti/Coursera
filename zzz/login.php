
<?php
$user=isset($_POST['who'])?$_POST['who']:"";
$pw=isset($_POST['pass'])?$_POST['pass']:"";
$s="XyZzy12*_"."$pw";
global $str;
    $check=hash("md5",$s);
    $md5=hash("md5","XyZzy12*_php123");
    if(!empty($_POST["log"])){
        if($user==NULL || $pw==NULL) {
            $str= "Username and password required";
        }
        elseif ($check!=$md5) {
            $str= "Incorrect Password";
        }
        else{
            $str=header("Location:game.php?name=".urlencode($_POST['who']));
       }
        
    }
    elseif(isset($_POST['cancel'])){
       $str=header("Location:index.php");
    
    }  


?>

<!DOCTYPE html>

<head><title>Pritam Chakraborty f7f9aec1</title></head>
<body>
<h1>Please Log In</h1>
<p>
<p style="color:red"><?=$str?></p>

<form method="POST">
<label for ="who">Name</label>
<input type ="text" name="who" size=40  id="who" ><br/>
<label for ="pw">Password</label>
<input type ="password" name="pass" size="40" id="pass">

<p><p></p></P>
<input type="submit" value="Log In" name="log" >

<input type="submit" value="Cancel" name="cancel">
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the three character name of the 
programming language used in this class (all lower case) 
followed by 123. -->
</p>

</style>
<!-- > -->
</form>
</body>
</html>


