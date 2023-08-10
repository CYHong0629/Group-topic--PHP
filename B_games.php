<?php

include("Conn.php");

$sql = "SELECT * FROM GAME_DATA";

// 執行查詢
$statement = $pdo->prepare($sql);
$statement->execute();

// 抓出全部且封裝成一個二維數組
$games = $statement->fetchAll(PDO::FETCH_ASSOC);

//關閉數據庫連接
unset($pdo);

// 輸出所有會員數據
echo json_encode($games);




?>