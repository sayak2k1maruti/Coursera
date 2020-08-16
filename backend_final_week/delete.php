<?php
	$pdo = new PDO('mysql:host=localhost;port=3306;dbname=Registry', 'AutoGrader', 'auto1234');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $profileDetails = null;
	session_start();
	if(isset($_POST['cancel'])){
		header("location:index.php");
		return;
	}
	if(isset($_POST['delete'])){
		$sql = $pdo -> prepare("DELETE FROM Profile WHERE profile_id = :id");
		$sql->execute(array(':id' => $_POST['profile_id']));
		$_SESSION['success'] = "Profile deleted";
		header("location:index.php");
		return;
	}
	if (isset($_GET['profile_id'])) {
		$sql = $pdo->prepare("SELECT first_name,last_name,profile_id FROM Profile WHERE profile_id = :id ");
		$sql->execute(array(
				':id' => $_GET['profile_id']
			)
		);
		$profileDetails = $sql -> fetch(PDO::FETCH_ASSOC);
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
<title>Sayak Das's Profile Add</title>
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
<h1>Deleteing Profile</h1>
<form method="post" action="delete.php">
<p>First Name:<?=htmlentities($profileDetails['first_name'])?></p>
<p>Last Name:<?=htmlentities($profileDetails['last_name'])?></p>
<input type="hidden" name="profile_id"
value="<?=htmlentities($profileDetails['profile_id'])?>"
/>
<input type="submit" name="delete" value="Delete">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</div>
</body>
</html>
