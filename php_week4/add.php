<?php
    session_start();
    if ( ! isset($_SESSION['name']) ) {
        die('Not logged in');
    }
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'AutoGrader', 'auto1234');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if(isset($_POST['cancel'])){
        header("location:view.php");
    }
    if(isset($_POST['submit'])){
        if( ! strlen($_POST['make']) < 1){
            if(is_numeric($_POST['year']) && is_numeric($_POST['mileage'])){
                $sql = $pdo->prepare('INSERT INTO autos(make, year, mileage,url) VALUES ( :mk, :yr, :mi, :url)');
                $sql ->execute(array(
                                    ':mk' => $_POST['make'],
                                    ':yr' => $_POST['year'],
                                    ':mi' => $_POST['mileage'],
                                    ':url' => $_POST['url'])
                                    );
                $_SESSION['success'] = "Record inserted";
                header("Location: view.php");
                return;
            }else{
                $_SESSION['error'] = "Mileage and year must be numeric";
                header("Location: add.php");
                return;
            }
        }else{
            $_SESSION['error'] = "Make is required";
            header("Location: add.php");
            return;
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
<title>Sayak Das's Automobile Tracker</title>
<link rel = 'icon' href = "icon.jpg">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>
<body>
<div class="container">
<h1>Tracking Autos for <?php echo(htmlentities($_SESSION['name']));?></h1>
<?php
    if( isset($_SESSION['error'])){
        echo("<p style=\"color: red;\">".htmlentities($_SESSION['error'])."</p>");
        unset($_SESSION['error']);
    }
?>
<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<p>Url(You can leave it blank):
<input type="text" name="url"/></p>
<input type="submit" value="Add" name = "submit">
<input type="submit" name="cancel" value="Cancel">
</form>
</ul>
</div>
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script></body>
</html>
