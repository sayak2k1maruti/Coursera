<?php
    $destination = "mysql:host=localhost:3306;dbname=Test";
    $user = "test";
    $password = "test1234";
    $pdo = new PDO($destination, $user, $password);
    
    if(isset($_POST['delete'])){
        if(is_numeric($_POST['delete_user'])){
            $sql = "DELETE FROM Users WHERE user_id = :user_id";
            $delete = $pdo -> prepare($sql);
            $delete -> execute(array(
                ':user_id' => $_POST['delete_user']
                ));
        }else{
            echo("<script>alert('Please enter a number in proper filed!!!');</script>");
        }
    }
    if(isset($_POST['user_name']) && isset($_POST['user_passwd'])){
        if(strlen($_POST['user_name'])>0 && strlen($_POST['user_passwd'])>0){
                if(strlen($_POST['user_name'])>3 && strlen($_POST['user_passwd'])>5){
                    $sql = "INSERT INTO Users(name,password) VALUES(:name,:password)";
                    $insert = $pdo -> prepare($sql);
                    $insert -> execute(array(
                        ':name' => $_POST['user_name'],
                        ':password' => $_POST['user_passwd']
                    ));
                }else{
                    echo("<script>alert('Length of name must be greater than 3 length of password must be greater than 5!!!');</script>");
                }
        }else{
            echo("<script>alert('Please fill all the filed!!!');</script>");
        }
    }
    $sql = "SELECT * FROM Users";
    $details = $pdo->query($sql);
    $output = array();
    while ($row = $details -> fetch(PDO::FETCH_ASSOC)) {
        $output[count($output)] = $row;
    } 
?>
<table caption = "Output">
<tr>
    <th>User_id</td>
    <th>Name</th>
    <th>password</th>
</tr>
<?php
    for($i=0;$i<count($output);$i++){
        echo("<tr>");
        echo("<td>".$output[$i]['user_id']."</td>");
        echo("<td>".$output[$i]['name']."</td>");
        echo("<td>".$output[$i]['password']."</td>");
        echo("</tr>");
    }
?>
</table>
<style>
    table{
        border:inset 1px #000000;
        margin:10px auto;
    }
    th, td {
        border: 1px solid black;
    }
</style>
<form method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="user_name" required>
        &nbsp;
        <label for="password">Password</label>
        <input type="password" id="password" name="user_passwd" required><br/><br/>
        <input type="submit" name="Create Account" value = "Create Account">
</form>
<br/>
<br/>
<form method="POST">
        <label for="delete">Delete User by User_id</label>
        <input type="number" id="delete" name="delete_user" required> 
        <input type="submit" name="delete" value = "DELETE">       
</form>
<pre>
<?php
    print_r($output);
?>
</pre>