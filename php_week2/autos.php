<?php
    $failure = false;
    $success = false;
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'AutoGrader', 'auto1234');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if(! isset($_GET['name'])){
        die("Name parameter missing");
    }

    if(isset($_POST['logout'])){
        header("location:login.php");
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
                $success = "Record inserted";
            }else{
                $failure = "Mileage and year must be numeric";
            }
        }else{
            $failure = "Make is required";
        }
    }
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
<h1>Tracking Autos for <?php echo(htmlentities($_GET['name']));?></h1>
<?php
    if($failure != false){
        echo("<p style=\"color: red;\">".$failure."</p>");
    }
    if($success != false){
        echo("<p style=\"color: green;\">".$success."</p>");
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

<h2>Automobiles</h2>
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
<ul>
<p>
</ul>
</div>
</body>
</html>