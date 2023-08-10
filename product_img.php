<?php

// 此檔為範例，請依據個人需求做更改

//資料庫連線(這行一定要寫!!!!!!!!!)
include("Conn.php");

// 撰寫 SQL 查詢
$sql = "SELECT GI.IMG_PATH, GD.GAME_ID FROM GAME_IMG GI
left join GAME_DATA GD on GI.GAME_ID = GD.GAME_ID";

$sql_where = " where GD.GAME_ID = ?"; 

//判斷前面傳來的id是否為空，是的話就是列表頁，不拚where條件的部分，不是就拚where條件
if(empty($_GET["id"])){
    $statement = $pdo->prepare($sql);
}else{
    $sql =  $sql.$sql_where;
    $statement = $pdo->prepare($sql);
    $statement->bindValue(1,$_GET["id"]);
}

// 執行查詢
$statement->execute();

//抓出全部且依照順序封裝成一個二維陣列
$productImg  = $statement->fetchAll();

// 建立最終的 JSON 格式陣列
$jsonArray = array();

// -----------在這下面做資料處理回傳你想要的格式-----------------

// if (count($testResult) > 0) {
//     foreach($testResult as $index => $testRow){

//     }
// }
// -----------在這上面做資料處理回傳你想要的格式-----------------

// 關閉資料庫連線
// unset($pdo); 

// 轉換成 JSON 格式輸出 
echo json_encode($productImg);
?>
