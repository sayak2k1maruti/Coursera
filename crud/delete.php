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
    if(isset($_POST['delete'])){
        $sql = "DELETE FROM autos WHERE autos_id = :id";
        $delete = $pdo->prepare($sql);
        $delete ->execute(array(
            ':id' => $_POST['autos_id']
        )
        );
        $_SESSION['success'] = "Record deleted";
        header("Location:index.php");
        return;
    }
?>
<html>
<head>
<title>Deleting...</title>
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

<p>Confirm: Deleting <?=htmlentities($fetchedData['make']);?></p>
<form method="post">
    <input type="hidden" name="autos_id" value=<?=htmlentities($fetchedData['autos_id'])?>>
    <input type="submit" value="Delete" name="delete">
    <a href="index.php">Cancel</a>
</form>
</div>
</body>