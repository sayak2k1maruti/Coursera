<?php
	$pdo = new PDO('mysql:host=localhost;port=3306;dbname=Registry', 'AutoGrader', 'auto1234');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $profileDetails = null;
    $positionDetails = array();
    $educationDetails = array();
    $schools = array();
	session_start();
	if (isset($_GET['profile_id'])) {
		$sql = $pdo->prepare("SELECT * FROM Profile WHERE profile_id = :id ");
		$sql->execute(array(
				':id' => $_GET['profile_id']
			)
		);
		$profileDetails = $sql -> fetch(PDO::FETCH_ASSOC);
		$sql = $pdo->prepare("SELECT * FROM `Position` WHERE `profile_id` = :pro_id ORDER BY ranks");
		$sql->execute(array(':pro_id' => $_GET['profile_id']));
		while ($row = $sql -> fetch(PDO::FETCH_ASSOC)) {
        	$positionDetails[count($positionDetails)] = $row;
    	}
    	$sql = $pdo -> prepare("SELECT * FROM `Education` WHERE `profile_id` = :id ORDER BY ranks");
    	$sql->execute(array(':id' => $_GET['profile_id']));
    	while ($row = $sql -> fetch(PDO::FETCH_ASSOC)) {
        	$educationDetails[count($educationDetails)] = $row;
        	$newSql = $pdo -> prepare("SELECT `name` FROM `Institution` WHERE `institution_id` = :id");
        	$newSql -> execute(array(':id'=>$row['institution_id']));
        	$newRow = $newSql -> fetch(PDO::FETCH_ASSOC);
        	$schools[count($schools)] = $newRow['name'];
    	}
		if($profileDetails == null){
			$_SESSION['error'] = "Could not load profile";
			header("location:index.php");
			return;
		}
	}else{
		$_SESSION['error'] = "Missing profile_id";
		header("location:index.php");
		return;
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
<h1>Profile information</h1>
<p><strong>First Name:</strong><?=htmlentities($profileDetails['first_name'])?></p>
<p><strong>Last Name:</strong><?=htmlentities($profileDetails['last_name'])?></p>
<p><strong>Email:</strong><?=htmlentities($profileDetails['email'])?></p>
<p><strong>Headline:</strong><br><?=htmlentities($profileDetails['headline'])?></p>
<p><strong>Summary:</strong><br><?=htmlentities($profileDetails['summary'])?></p>
<p><strong>Image:</strong><img src="<?=htmlentities($profileDetails['image_url'])?>" alt="Image Not Found" style = "width: 200px;">
<p>
</p>
<p>
	<?php
		if(count($educationDetails) > 0){
			echo("<strong>Education:</strong><br>");
			echo("<ul>");
			for ($i=0; $i < count($educationDetails); $i++) { 
				echo("<li>".htmlentities($educationDetails[$i]['year']).":".htmlentities($schools[$i])."</li>");
			}
			echo("</ul>");
		}
		if(count($positionDetails) > 0){
			echo("<strong>Position:</strong><br>");
			echo("<ul>");
			for ($i=0; $i < count($positionDetails); $i++) { 
				echo("<li>".htmlentities($positionDetails[$i]['year']).":".htmlentities($positionDetails[$i]['description'])."</li>");
			}
			echo("</ul>");
		}
	?>
</p>
<a href="index.php">Done</a>
</div>
</body>
</html>