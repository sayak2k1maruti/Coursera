<?php
    require_once "pdo.php";
    session_start();
    if(! isset($_SESSION['user'])){
        die('ACCESS DENIED');
    }
    if(isset($_POST['cancel'])){
        header("Location:index.php");
        return;
    }
    if(isset($_POST['add'])){
        $make = $_POST['make'];
        $model = $_POST['model']; 
        $year =  $_POST['year']; 
        $mileage = $_POST['mileage']; 
        $url = $_POST['url'];
        if(strlen($make)>0 && strlen($model)>0 && strlen($year)>0 && strlen($mileage)>0 ){
            if(is_numeric($year)){
                if(is_numeric($mileage)){
                    $sql = "INSERT INTO autos(make,model,year,mileage,url) VALUES(:mk,:md,:yr,:mi,:ul)";
                    $insert = $pdo->prepare($sql);
                    $insert ->execute(array(
                        ':mk' => $make,
                        ':md' => $model,
                        ':yr' => $year,
                        ':mi' => $mileage,
                        ':ul' => $url
                    )
                    );
                    $_SESSION['success'] = "Record added.";
                    header("Location:index.php");
                    return;
                }else{
                    $_SESSION['error'] = "Mileage must be numeric";
                    header("Location:add.php");
                    return;
                }
            }else{
                $_SESSION['error'] = "Year must be numeric";
                header("Location:add.php");
                return;
            }
        }
        else{
            $_SESSION['error'] = "All fields are required (except url)";
            header("Location:add.php");
            return;
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
<title>Sayk Das Automobile Tracker</title>
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
<h1>Tracking Automobiles for <?php echo(htmlentities($_SESSION['user'])); ?></h1>
<?php 
    if(isset($_SESSION['error'])){
        echo("<p style=\"color: red;\">".htmlentities($_SESSION['error'])."</p>");
        unset($_SESSION['error']);
    }
?>
<form method="post">
<p>Make:

<input type="text" name="make" size="40"/></p>
<p>Model:

<input type="text" name="model" size="40"/></p>
<p>Year:

<input type="text" name="year" size="10"/></p>
<p>Mileage:

<input type="text" name="mileage" size="10"/></p>
<p>Picture_Url(You can leave it blank):
<input type ="text" name = "url" size="50"></p>
<input type="submit" name='add' value="Add">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
</div>
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script></body>
</html>
