<?php
    function check($computer,$human){
        /*I am using modulo function to make algorithm easier*/
        if(($computer-$human+3)%3==1){
            return "You Lose";
        }elseif(($computer-$human+3)%3==2){
            return "You Win";
        }else{
            return "Tie";
        }
    }
    $values = array("Rock","Paper","Scissors");
    $faliure = false;
    $result = false;
    if(isset($_POST['logout'])){
        header("location:login.php");
    }
    if(! isset($_GET['name'])){
        die("Name parameter missing");
    }
    if(isset($_POST['play'])){
        if($_POST['human'] == '-1'){
            $faliure = "Please select a strategy and press Play.";
        }elseif($_POST['human']== '3'){
            for($i=0;$i<3;$i++){
                for($j=0;$j<3;$j++){
                    $result .= "Your Play=$values[$i] Computer Play=$values[$j] Result=".check($j,$i)."\n";
                }
            }
        }else{
            $computer = rand(0,2);
            $result = "Your Play=".$values[(int)$_POST['human']]." Computer Play=".$values[(int)$computer]." Result=".check($computer,(int)$_POST['human']);
        }
    }else{
        $faliure = "Please select a strategy and press Play.";
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Pritam Chakraborty f7f9aec1  Rock-Papaer-Sessior game</title>
    </head>
    <body>
        <div class="container">
            <h1>Rock Paper Scissors</h1>
            <div>
            <?php
                print "<p style=\"text-align:center;font-size:110%;\">"."<span style = \"font-size:80%;\">Welcome:</span>".htmlentities($_GET['name'])."</p>";
            ?>
                <h2>Play</h2>
                <form method="POST">
                    <label for="select">Select:</label>
                    <select id="select" name="human" class="select">
                        <option value="-1">--Select--</option>
                        <option value="0">Rock</option>
                        <option value="1">Paper</option>
                        <option value="2">Scissors</option>
                        <option value="3">Test</option>
                    </select>
                    <br/>
                    <input type="submit" value="Play" name="play" class="botton play"><br/>
                    <input type="submit" value="LogOut" name="logout">
                </form>
            </div>
            <div class="result">
                <h2>Result</h2>
                <?php
                    if($faliure != false){
                        echo("<p>".$faliure."</p>");
                    }
                    if($result != false){
                        echo("<pre>$result</pre>");
                    }
                ?>
            </div>
        </div>
    </body>
</html>
