<?php 
    require_once "pdo.php";
    session_start();
    if(! isset($_SESSION['user'])){
        die('ACCESS DENIED');
    }
    if(! isset($_GET['autos_id'])){
        $_SESSION['error'] = "Bad value for id";
        header("Location:index.php");
        return;
    }
    if(! is_numeric($_GET['autos_id'])){
        $_SESSION['error'] = "Bad value for id";
        header("Location:index.php");
        return;
    }
    $sql = "SELECT * FROM `autos` WHERE autos_id = :id";
    $retrive = $pdo->prepare($sql);
    $retrive ->execute(array(
        ':id' => $_GET['autos_id']
    )
    );
    $fetchedData = $retrive -> fetch(PDO::FETCH_ASSOC);
    if($fetchedData == null){
        $_SESSION['error'] = "Bad value for id";
        header("Location:index.php");
        return;
    }
    if(isset($_POST['cancel'])){
        header("Location:index.php");
        return;
    }
    if(isset($_POST['save'])){
        $make = $_POST['make'];
        $model = $_POST['model']; 
        $year =  $_POST['year']; 
        $mileage = $_POST['mileage']; 
        $url = $_POST['url'];
        $id = $_POST['autos_id'];
        if(strlen($make)>0 && strlen($model)>0 && strlen($year)>0 && strlen($mileage)>0 ){
            if(is_numeric($year)){
                if(is_numeric($mileage)){
                    $sql = "UPDATE autos SET make = :mk , model = :md ,year = :yr, mileage = :mi , url = :ul WHERE autos_id = :id";
                    $insert = $pdo->prepare($sql);
                    $insert ->execute(array(
                        ':mk' => $make,
                        ':md' => $model,
                        ':yr' => $year,
                        ':mi' => $mileage,
                        ':ul' => $url,
                        ':id' => $id
                    )
                    );
                    $_SESSION['success'] = "Record Updated.";
                    header("Location:index.php");
                    return;
                }else{
                    $_SESSION['error'] = "Mileage must be numeric";
                    header("Location:edit.php?autos_id=".$fetchedData['autos_id']);
                    return;
                }
            }else{
                $_SESSION['error'] = "Year must be numeric";
                header("Location:edit.php?autos_id=".$fetchedData['autos_id']);
                return;
            }
        }
        else{
            $_SESSION['error'] = "All fields are required (except url)";
            header("Location:edit.php?autos_id=".$fetchedData['autos_id']);
            return;
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
<title>Sayak Das's Automobile Tracker</title>
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
<h1>Editing Automobile</h1>
<?php 
    if(isset($_SESSION['error'])){
        echo("<p style=\"color: red;\">".htmlentities($_SESSION['error'])."</p>");
        unset($_SESSION['error']);
    }
?>
<form method="post">
<p>Make : <input type="text" name="make" size="40" value= <?=htmlentities($fetchedData['make']);?>></p>
<p>Model : <input type="text" name="model" size="40" value= <?=htmlentities($fetchedData['model']);?>></p>
<p>Year : <input type="text" name="year" size="10" value= <?=htmlentities($fetchedData['year']);?>></p>
<p>Mileag : <input type="text" name="mileage" size="10" value= <?=htmlentities($fetchedData['mileage']);?>></p>
<p>PictureUrl : <input type="text" name="url" size="50" value= <?=htmlentities($fetchedData['url']);?>></p>
<input type="hidden" name="autos_id" value=<?=htmlentities($fetchedData['autos_id']);?>>
<input type="submit" name = "save" value="Save">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
</div>
</body>
</html>
