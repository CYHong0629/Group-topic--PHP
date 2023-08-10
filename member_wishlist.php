<?php

include("Conn.php");

// =========請求==========
// 設定CORS標頭，允許 'content-type' 標頭欄位。
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");


// 撰寫 SQL 查詢
$sql = 
"SELECT WL.GAME_ID from WISH_LIST WL
LEFT JOIN MEMBER_DATA MD on WL.MEMBER_ID = MD.MEMBER_ID
where MD.MEMBER_ID = ?;";


// 執行查詢
$statement = $pdo->prepare($sql);
$statement->bindValue(1,$_GET["id"]);
$statement->execute();


$testResult  = $statement->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($testResult);

?>