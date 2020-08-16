<?php
	$pdo = new PDO('mysql:host=localhost;port=3306;dbname=Registry', 'AutoGrader', 'auto1234');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	session_start();
	if(!isset($_SESSION['user_id'])){
		die("ACCESS DENIED");
	}
	if(isset($_POST['cancel'])) {
		header("location:index.php");
		return;
	}
	if(isset($_POST['add'])){
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$email = $_POST['email'];
		$headline = $_POST['headline'];
		$summary = $_POST['summary'];
		$url = $_POST['url'];
		if (strlen($first_name)>0 && strlen($last_name) >0 && strlen($email) >0  && strlen($headline)>0 && strlen($summary) >0) {
			if(strpos($email,'@')){
				if (strlen($url) >0) {
					if(filter_var($url, FILTER_VALIDATE_URL) === false){
						$_SESSION['error']= "Enter a proper url or leave it blank";
						header("location:add.php");
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
				$sql = $pdo->prepare('INSERT INTO Profile(user_id, first_name,last_name,email,headline,summary,image_url) VALUES ( :u_i, :fn, :ln, :em,:hl,:sm,:url)');
                $sql ->execute(array(
                                    ':u_i' => $_SESSION['user_id'],
                                    ':fn' => $first_name,
                                    ':ln' => $last_name,
                                    ':em' => $email,
                                    ':hl' => $headline,
                                    ':sm' => $summary,
                                    ':url' => $url
                                	)
                                    );
               	$profile_id = $pdo->lastInsertId();
               	$rank = 1;
               	$eduRank = 1;
               	for ($i=1; $i<=9 ; $i++) { 
               		if(isset($_POST['year'.$i])){
               			$stmt = $pdo->prepare('INSERT INTO `Position`(`profile_id`, `ranks`, `year`, `description`) VALUES ( :pid, :rank, :year, :des)');
        				$stmt->execute(array(
            				':pid' => $profile_id,
            				':rank' => $rank,
            				':year' => $_POST['year'.$i],
            				':des' => $_POST['desc'.$i])
        						);
        				$rank++;
               		}else continue;
               		}
               		for ($i=1; $i<=9 ; $i++) { 
               			$institution_id = null;
               		if(isset($_POST['edu_year'.$i])){
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
            				':pi' => $profile_id,
            				':id' => $institution_id,
            				':r' => $eduRank,
            				':y' => $_POST['edu_year'.$i])
        						);
        				$eduRank++;
               		}else continue;
               		}
                $_SESSION['success'] = "Profile added";
                header("location:index.php");
			}else{
				$_SESSION['error'] = "Email address must contain @";
				header("location:add.php");
				return;
			}
		}else{
			$_SESSION['error'] = "All fields are required except imageurl";
			header("location:add.php");
			return;
		}
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
<body>
<div class="container">
<h1>Adding Profile for <?=htmlentities($_SESSION['name'])?></h1>
<?php
    if( isset($_SESSION['error']) ){
        echo("<p style=\"color: red;\">".$_SESSION['error']."</p>");
        unset($_SESSION['error']);
    }
?>
<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60"/></p>
<p>Email:
<input type="text" name="email" size="30"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"></textarea>
<p>ImageUrl:
<input type="text" name="url" size="40"/></p>
<p>
Education: <input type="submit" id="addEdu" value="+">
<div id="edu_fields">
</div>
</p>
<p>
Position: <input type="submit" id="addPos" value="+">
<div id="position_fields">
</div>
</p>
<p>
<input type="submit" name = "add" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</div>
<script>
var countPos = 0;
var countEdu = 0
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
