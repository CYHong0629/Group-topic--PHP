<?php

// 此檔為範例，請依據個人需求做更改

//資料庫連線(這行一定要寫!!!!!!!!!)
include("Conn.php");

// 撰寫 SQL 查詢
$sql = "SELECT * FROM 你要撈的資料表";

// 執行查詢
$statement = $pdo->prepare($sql);
$statement->execute();

//抓出全部且依照順序封裝成一個二維陣列
$testResult  = $statement->fetchAll();

// 建立最終的 JSON 格式陣列
$jsonArray = array();

// -----------在這下面做資料處理回傳你想要的格式-----------------

if (count($testResult) > 0) {
    foreach($testResult as $index => $testRow){

    }
}
// -----------在這上面做資料處理回傳你想要的格式-----------------

// 關閉資料庫連線
unset($pdo); 

// 轉換成 JSON 格式輸出 
echo json_encode($jsonArray);
?>
