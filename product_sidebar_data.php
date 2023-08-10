<?php

include("Conn.php");

// 撰寫 SQL 查詢
$sql_activity = "SELECT * FROM ACTIVITY";

$sql_category = "SELECT * FROM CATEGORY";

// 執行查詢
$statement_activity = $pdo->prepare($sql_activity);
$statement_category = $pdo->prepare($sql_category);
$statement_activity->execute();
$statement_category->execute();

//抓出全部且依照順序封裝成一個二維陣列
$activityResult  = $statement_activity->fetchAll();
$categoryResult  = $statement_category->fetchAll();

// 建立最終的 JSON 格式陣列
$jsonArray = array(
    "activity" => $activityResult,
    "category" => $categoryResult
);


// 關閉資料庫連線
// unset($pdo); 

// 轉換成 JSON 格式輸出 
echo json_encode($jsonArray);
?>
