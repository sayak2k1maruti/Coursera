<?php
	$pdo = new PDO('mysql:host=localhost;port=3306;dbname=Registry', 'AutoGrader', 'auto1234');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $profileDetails = null;
    $positionDetails = array();
    $educationDetails = array();
    $schools = array();
	session_start();
	if(isset($_POST['cancel'])) {
		header("location:index.php");
		return;
	}
	if(isset($_POST['save'])){
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$email = $_POST['email'];
		$headline = $_POST['headline'];
		$summary = $_POST['summary'];
		$url = $_POST['url'];
		$id = $_POST['profile_id'];
		if (strlen($first_name)>0 && strlen($last_name) >0 && strlen($email) >0  && strlen($headline)>0 && strlen($summary) >0) {
			if(strpos($email,'@')){
				if (strlen($url) >0) {
					$check = "http";
					if(filter_var($url, FILTER_VALIDATE_URL) === false){
						$_SESSION['error']= "Enter a proper url or leave it blank";
						header("location:edit.php?profile_id=".$_POST['profile_id']);
						return;
					}
				}
				for ($i=1; $i<=9 ; $i++) { 
               		if(isset($_POST['year'.$i])){
               			if (strlen($_POST['year'.$i]) && strlen($_POST['desc'.$i])>0){
               				if (! is_numeric($_POST['year'.$i])) {
               					$_SESSION['error'] = "Position year must be numeric";
               					header("location:add.php");
               					return;
               				}
               			}
               			else{
							$_SESSION['error'] = "All fields are required except imageurl";
               				header("location:add.php");
               				return;
               			}
               		}
               		if(isset($_POST['edu_year'.$i])){
               			if (strlen($_POST['edu_year'.$i]) && strlen($_POST['edu_school'.$i])>0){
               				if (! is_numeric($_POST['edu_year'.$i])) {
               					$_SESSION['error'] = "Education year must be numeric";
               					header("location:add.php");
               					return;
               				}
               			}
               			else{
							$_SESSION['error'] = "All fields are required except imageurl";
               				header("location:add.php");
               				return;
               			}
               		}
               		}
				$sql = $pdo->prepare("UPDATE Profile SET  first_name = :fn   , last_name = :ln ,email = :email ,headline = :hl , summary = :sm , image_url = :url WHERE profile_id = :pi");
                $sql ->execute(array(
                                    ':fn' => $first_name,
                                    ':ln' => $last_name,
                                    ':email' => $email,
                                    ':hl' => $headline,
                                    ':sm' => $summary,
                                    ':url' => $url,
                                    ':pi' => $id
                                	)
                                    );
                $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
    			$stmt->execute(array( ':pid' => $_POST['profile_id']));
    			$stmt = $pdo->prepare('DELETE FROM Education WHERE profile_id=:pid');
    			$stmt->execute(array( ':pid' => $_POST['profile_id']));
    			// Insert the position entries
    			$rank = 1;
    			$eduRank = 1;
    			for($i=1; $i<=9; $i++) {
        			if ( ! isset($_POST['year'.$i]) ) continue;
        			if ( ! isset($_POST['desc'.$i]) ) continue;
        			$year = $_POST['year'.$i];
        			$desc = $_POST['desc'.$i];
        			$stmt = $pdo->prepare('INSERT INTO `Position`(`profile_id`, `ranks`, `year`, `description`) VALUES ( :pid, :rank, :year, :des)');
        				$stmt->execute(array(
            				':pid' => $_POST['profile_id'],
            				':rank' => $rank,
            				':year' => $_POST['year'.$i],
            				':des' => $_POST['desc'.$i])
        						);
        				$rank++;
    			}
    			for ($i=0; $i <= 9 ; $i++) { 
    				if(!isset($_POST['edu_year'.$i])) continue;
    				if(!isset($_POST['edu_school'.$i])) continue;
    				$institution_id = null;
    				$sql = $pdo->prepare("SELECT institution_id FROM Institution WHERE name = :name");
               		$sql -> execute(array(':name' => $_POST['edu_school'.$i]));
               		$row = null;
               		$row = $sql -> fetch(PDO::FETCH_ASSOC);
               		if ($row == null) {
               			$sql = $pdo -> prepare("INSERT INTO Institution(`name`) VALUE( :val )");
               			$sql -> execute(array(':val' => $_POST['edu_school'.$i]));
               			$institution_id = $pdo -> lastInsertId();
               		}else{
               			$institution_id = $row['institution_id'];
               		}
               		$stmt = $pdo->prepare("INSERT INTO Education(`profile_id`,`institution_id`,`ranks`,`year`) VALUES( :pi , :id , :r , :y)");
        				$stmt->execute(array(
            				':pi' => $_POST['profile_id'],
            				':id' => $institution_id,
            				':r' => $eduRank,
            				':y' => $_POST['edu_year'.$i])
        						);
        			$eduRank++;
               	}
                $_SESSION['success'] = "Profile updated";
                header("location:index.php");
                return;
			}else{
				$_SESSION['error'] = "Email address must contain @";
				header("location:edit.php?profile_id=".$_POST['profile_id']);
				return;
			}
		}else{
			$_SESSION['error'] = "All fields are required except imageurl";
			header("location:edit.php?profile_id=".$_POST['profile_id']);
			return;
		}
	}
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
<title>Sayak Das's Profile Edit</title>
<link rel="icon"  href="../icon.jpg">
<!-- bootstrap.php - this is HTML -->

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
    crossorigin="anonymous">

<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
    crossorigin="anonymous">

<link rel="stylesheet" 
    href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" 
    integrity="sha384-xewr6kSkq3dBbEtB6Z/3oFZmknWn7nHqhLVLrYgzEFRbU/DHSxW7K3B44yWUN60D" 
    crossorigin="anonymous">
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
<h1>Editing Profile for UMSI</h1>
<?php
    if( isset($_SESSION['error']) ){
        echo("<p style=\"color: red;\">".$_SESSION['error']."</p>");
        unset($_SESSION['error']);
    }
?>
<form method="post" action="edit.php">
<p>First Name:
<input type="text" name="first_name" size="60"
value="<?=htmlentities($profileDetails['first_name'])?>"
/></p>
<p>Last Name:
<input type="text" name="last_name" size="60"
value="<?=htmlentities($profileDetails['last_name'])?>"
/></p>
<p>Email:
<input type="text" name="email" size="30"
value="<?=htmlentities($profileDetails['email'])?>"
/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"
value="<?=htmlentities($profileDetails['headline'])?>"
/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80">
<?=htmlentities($profileDetails['summary'])?></textarea>
<p>
<input type="hidden" name="profile_id"
value="<?=htmlentities($profileDetails['profile_id'])?>"
/>
<p>ImageUrl:
<input type="text" name="url" size="40" value="<?=htmlentities($profileDetails['image_url'])?>" /></p>
<p>
Education: <input type="submit" id="addEdu" value="+">
<div id="edu_fields">
	<?php
		for ($i=1; $i <= count($educationDetails); $i++) { 
			echo("<div id=\"edu".$i."\"><p>Year: <input type=\"text\" name=\"edu_year".$i."\" value=".htmlentities($educationDetails[$i-1]['year'])." />");
			echo("<input type=\"button\" value=\"-\" onclick=\"$('#edu".$i."').remove();return false;\"></p>");
			echo("<p>School: <input type=\"text\" size=\"80\" name=\"edu_school".$i."\" class=\"school\"
value= \"".htmlentities($schools[$i -1])."\""." />");
			echo("</div>");
		}
	?>
</div>
</p>
<p>
Position: <input type="submit" id="addPos" value="+">
<div id="position_fields">
	<?php
		for ($i=1; $i <= count($positionDetails); $i++) { 
			echo("<div id=\"position".$i."\">");
			echo("<p>Year: <input type=\"text\" name=\"year".$i."\" value=\"".htmlentities($positionDetails[$i-1]['year'])."\" />");
			echo("<input type=\"button\" value=\"-\" onclick=\"$('#position".$i."').remove();return false;\">");
			echo("</p>");
			echo("<textarea name=\"desc".$i."\" rows=\"8\" cols=\"80\">".htmlentities($positionDetails[$i-1]['description'])."</textarea>");
			echo("</div>");
		}
	?>
</div>
</p>
<input type="submit" name = "save" value="Save">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</div>
<script>
countPos = <?=count($positionDetails)?> ;
countEdu = <?=count($educationDetails)?>;
$(document).ready(function(){
    window.console && console.log('Document ready called');
    $('#addPos').click(function(event){
        // http://api.jquery.com/event.preventdefault/
        event.preventDefault();
        if ( countPos >= 9 ) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        countPos++;
        window.console && console.log("Adding position "+countPos);
        $('#position_fields').append(
            '<div id="position'+countPos+'"> \
            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
            <input type="button" value="-" \
            onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });
    $('#addEdu').click(function(event){
        event.preventDefault();
        if ( countEdu >= 9 ) {
            alert("Maximum of nine education entries exceeded");
            return;
        }
        countEdu++;
        window.console && console.log("Adding education "+countEdu);

        $('#edu_fields').append(
            '<div id="edu'+countEdu+'"> \
            <p>Year: <input type="text" name="edu_year'+countEdu+'" value="" /> \
            <input type="button" value="-" onclick="$(\'#edu'+countEdu+'\').remove();return false;"><br>\
            <p>School: <input type="text" size="80" name="edu_school'+countEdu+'" class="school" value="" />\
            </p></div>'
        );

        $('.school').autocomplete({
            source: "school.php"
        });

    });
});
</script>
</body>
</html>
	