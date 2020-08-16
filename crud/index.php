<?php
  require_once "pdo.php";
  session_start();
  $output = array();
  $sql = "SELECT * FROM `autos` ORDER BY make";
  $output = array();
  if(isset($_SESSION['user'])){
    $details = $pdo->query($sql);
    while ($row = $details -> fetch(PDO::FETCH_ASSOC)) {
      $output[count($output)] = $row;
    } 
  }
?>
<!DOCTYPE html>
<html>
<head>
<title>Sayak Das's Index Page</title>
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
<h2>Welcome to the Automobiles Database</h2>
<?php
  if(isset($_SESSION['success'])){
    echo("<p style=\"color: green;\">".htmlentities($_SESSION['success'])."</p>");
    unset($_SESSION['success']);
  }
  if(isset($_SESSION['error'])){
    echo("<p style=\"color: red;\">".htmlentities($_SESSION['error'])."</p>");
    unset($_SESSION['error']);
  }
  if(! isset($_SESSION['user'])){
    echo("<p><a href=\"login.php\">Please log in</a></p>
    <p>Attempt to <a href=\"add.php\">add data</a> without logging in</p>");
  }
  else{
    if(count($output)>0){
      echo("<table>");
      echo("<tr>");
      echo("<th>Make</th>");
      echo("<th>Model</th>");
      echo("<th>Year</th>");
      echo("<th>Mileage</th>");
      echo("<th>Piture</th>");	
      echo("<th>Action</th>");
      echo("</tr>");
      for($i=0;$i<count($output);$i++){
      echo("<tr>");
      echo("<td>".htmlentities($output[$i]['make'])."</td>");
      echo("<td>".htmlentities($output[$i]['model'])."</td>");
      echo("<td>".htmlentities($output[$i]['year'])."</td>");
      echo("<td>".htmlentities($output[$i]['mileage'])."</td>");	
      echo("<td><a href = \"".htmlentities($output[$i]['url'])."\" target = _blank>".htmlentities(substr($output[$i]['url'],0,10))."..."."</a></td>");
      echo("<td><a href=\"edit.php?autos_id=".$output[$i]['autos_id']."\">Edit</a> / <a href=\"delete.php?autos_id=".$output[$i]['autos_id']."\">Delete</a></td>");
      echo("</tr>");
      }
      echo("</table>");
    }else{
      echo("<p>No rows found</p>");
    }
    echo("<p><a href=\"add.php\">Add New Entry</a></p>
    <p><a href=\"logout.php\">Logout</a></p>");
  }
?>
<style>
    table{
        border:1px #000000;
        margin:10px;
    }
    th, td {
        border: 1px solid black;
        padding: 1px 5px;
        text-align:center;
    }
</style>
</body>
</html>
