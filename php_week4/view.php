<?php
    session_start();
    if ( ! isset($_SESSION['name']) ) {
        die('Not logged in');
    }
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'AutoGrader', 'auto1234');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM `autos` ORDER BY make";
    $details = $pdo->query($sql);
    $output = array();
    while ($row = $details -> fetch(PDO::FETCH_ASSOC)) {
        $output[count($output)] = $row;
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
    if( isset($_SESSION['success']) ){
        echo("<p style=\"color: green;\">".$_SESSION['success']."</p>");
        unset($_SESSION['success']);
    }
?>
<h2>Automobiles</h2>
<ul>
<p>
</ul>
<p>
<a href="add.php">Add New</a> |
<a href="logout.php">Logout</a>
</p>
<table>
<tr>
    <th>Make</td>
    <th>Year</th>
    <th>Mileage</th>
    <th>URL</th>
</tr>
<?php
    for($i=0;$i<count($output);$i++){
        echo("<tr>");
        echo("<td>".htmlentities($output[$i]['make'])."</td>");
        echo("<td>".htmlentities($output[$i]['year'])."</td>");
        echo("<td>".htmlentities($output[$i]['mileage'])."</td>");
        echo("<td>"."<a href = \"".htmlentities($output[$i]['url'])."\" target = _blank>".htmlentities($output[$i]['url'])."</a>"."</td>");
        echo("</tr>");
    }
?>
</table>
<style>
    table{
        border:inset 1px #000000;
        margin:10px;
    }
    th, td {
        border: 1px solid black;
        padding: 2px 10px;
        text-align:center;
    }
</style>
</div>

<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script></body>
</html>
