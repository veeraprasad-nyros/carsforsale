<?php
require("dbcontroller.php");

$conn = new DBController();

$name   = $_POST['name'];
$car_id = $_POST['car_id'];
$rate   = $_POST['rate'];


if (!empty($name) && !empty($car_id) && !empty($rate)) {

    //user existed checking
    $sql = "SELECT `id` FROM `guest_tbl` WHERE `name`='" . $name . "'";
  	$rows = $conn->numRows($sql);
  	if($rows == 0){
        $sql1 = "INSERT INTO `guest_tbl` (`name`) VALUES ('".$name."')";
        $conn->runQuery($sql1);
        $sql2 = "INSERT INTO `review_tbl` (`guest_id`,`car_id`,`rate`) VALUES ((SELECT `id` FROM `guest_tbl` WHERE `name` ='".$name."'),".$car_id.",".$rate.")" ;
        $conn->runQuery($sql2);

        echo "1";
     }
    else{
        //user already rated checking
        $sql3 = "SELECT * FROM `review_tbl` WHERE `car_id`=".$car_id." AND `guest_id` = (SELECT `id` FROM `guest_tbl` WHERE `name` = '".$name."')";
        $result1 = $conn->numRows($sql3);
       
        if($result1 == 0){
            $sql3 = "INSERT INTO `review_tbl` (`guest_id`,`car_id`,`rate`) VALUES ((SELECT `id` FROM `guest_tbl` WHERE `name` ='".$name."'),".$car_id.",".$rate.")" ; 
            $conn->runQuery($sql3);
            echo "1";
        }
        else{
        	echo "0";	
        } 
    } 
} 

?>