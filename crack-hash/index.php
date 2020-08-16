<!DOCTYPE html>
<html lang = 'en'>
    <head>
        <title>Sayak Das's md5 code cracker</title>
    </head>
    <body>
    <h1>MD5 cracker</h1>
    <p>This application takes an MD5 hash of a four digit pin and check all 10,000 possible four digit PINs to determine the PIN.</p>
    <pre>
Debug Output:
    <?php
        $originalpin = "Not Found";
        $total = 0;
        $numbers = array(0,1,2,3,4,5,6,7,8,9);
        if(isset($_GET['md5'])){
            $get = $_GET['md5'];
            $show = 15;
            $time_pre = microtime(true);
            for($i=0;$i<count($numbers);$i++){
                for($j = 0;$j<count($numbers);$j++){
                    for($k=0;$k<count($numbers);$k++){
                        for($l=0;$l<count($numbers);$l++){
                            $try = $numbers[$i].$numbers[$j].$numbers[$k].$numbers[$l];
                            $check = hash('md5',$try);
                            $total++;
                            if($check==$get){
                                $originalpin = $try;
                                break;
                            }
                            if($show>0){
                                print "\n$check $try";
                                $show--;
                            }     
                        }
                    }
                }
            }
        $time_post = microtime(true);
        print "\nTotal check: $total";
        print "\nElapsed time: ";
        print $time_post-$time_pre;
        print "\n";
        }
    ?>
    </pre>
    <p>PIN:<?php echo("$originalpin"); ?></p>
    <form>
        <input type="text" name="md5" size="60"/>
        <input type="submit" value="Crack MD5"/>
    </form>
    <ul>
        <li><a href="index.php">Reset This Page</a></li>
        <li><a href="makecode.php">MD5 Code Maker</a></li>
    </ul>
    </body>
</html>