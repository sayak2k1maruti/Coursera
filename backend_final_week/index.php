<?php
	$pdo = new PDO('mysql:host=localhost;port=3306;dbname=Registry', 'AutoGrader', 'auto1234');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$loggedIn = 0;
	$resultArray = array();
	session_start();
	$user_id = null;
	$sql = "SELECT first_name,last_name,headline,profile_id,user_id FROM `Profile` ORDER BY first_name";
	$details = $pdo->query($sql);
	while ($row = $details -> fetch(PDO::FETCH_ASSOC)) {
        $resultArray[count($resultArray)] = $row;
    }
	if (isset($_SESSION['user_id'])) {
		$loggedIn = 1;
		$user_id = $_SESSION['user_id'];
	}else{
		$loggedIn = 0;
	}
?>
<!DOCTYPE html>
<html>
<head>
<title>Sayak Das's Resume Registry</title>
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
<h1>Sayak Das's Resume Registry</h1>
<?php
    if( isset($_SESSION['error']) ){
        echo("<p style=\"color: red;\">".$_SESSION['error']."</p>");
        unset($_SESSION['error']);
    }
	if(isset($_SESSION['success'])){
		echo("<p style=\"color: green;\">".$_SESSION['success']."</p>");
		unset($_SESSION['success']);
	}
	if($loggedIn){
		echo("<p><a href=\"logout.php\">Logout</a></p>");
	}else{
		echo("<p><a href=\"login.php\">Please log in</a></p>");
	}
?>

<?php
	if(count($resultArray)!=0){
		echo("<table>");
		echo ("<tr>");
		echo("<th>Name</th>");
		echo("<th>Headline</th>");
		if($loggedIn){
			echo("<th>Action</th>");
		}
		echo("</tr>");
		for ($i=0; $i <count($resultArray) ; $i++) { 
			echo ("<tr>");
			echo("<td><a href = view.php?profile_id=".htmlentities($resultArray[$i]['profile_id']).">".htmlentities($resultArray[$i]['first_name'])." ".htmlentities($resultArray[$i]['last_name'])."</a>"."</td>");
			echo("<td>".htmlentities($resultArray[$i]['headline'])."</td>");
			if($loggedIn){
				if($resultArray[$i]['user_id'] == $user_id){
					echo("<td><a href=\"edit.php?profile_id=".htmlentities($resultArray[$i]['profile_id'])."\">Edit</a> <a href=\"delete.php?profile_id=".htmlentities($resultArray[$i]['profile_id'])."\">Delete</a></td>");
				}else{
					echo("<td>Disable</td>");
				}
			}
			echo("</tr>");
		}
		echo ("</table>");
	}
	if($loggedIn){
		echo("<p><a href=\"add.php\">Add New Entry</a></p>");
	}
?>
</div>
</body>
<style>
    table{
        border:inset 1px #000000;
        margin:10px auto;
    }
    th, td {
        border: 1px solid black;
        padding: 2px 10px;
        text-align:center;
    }
</style>
</html>