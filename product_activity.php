<?php

include("Conn.php");

$sql = "SELECT A.ACTIVITY_ID,A.ACTIVITY_NAME,A.DISCOUNT_PERCENTAGE,GA.GAME_ID 
FROM ACTIVITY AS A
JOIN G_A_RELATION AS GA ON A.ACTIVITY_ID=GA.ACTIVITY_ID
WHERE A.ACTIVITY_ID = ?";

$statement = $pdo->prepare($sql);
$statement->bindValue(1,$_GET['activityId']);
$statement->execute();
$data = $statement->fetch(PDO::FETCH_OBJ);
$jsonArray = array(
    "activityId"=> $data->ACTIVITY_ID,
    "activityName"=> $data->ACTIVITY_NAME,
    "discount"=> $data->DISCOUNT_PERCENTAGE,
 );

echo json_encode($jsonArray);

?>
