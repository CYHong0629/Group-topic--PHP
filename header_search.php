<?php

// 此檔為範例，請依據個人需求做更改

//資料庫連線(這行一定要寫!!!!!!!!!)
include("Conn.php");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

// 撰寫 SQL 查詢
// $sql = "SELECT * FROM 你要撈的資料表";

$keyword = $_GET['keyword']; // 获取关键字参数的值
$sql = "SELECT GAME_ID, GAME_COVER, GAME_NAME FROM GAME_DATA WHERE GAME_NAME LIKE ?";


// 執行查詢
$statement = $pdo->prepare($sql);
// $statement->execute();
$statement->execute(["%$keyword%"]); // 將關鍵字綁定到查詢中的LIKE子句

//抓出全部且依照順序封裝成一個二維陣列
// $testResult  = $statement->fetchAll();
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

// 建立最終的 JSON 格式陣列
$jsonArray = array();

// -----------在這下面做資料處理回傳你想要的格式-----------------
if (count($results) > 0) {
    foreach($results as $row) {
        $item = array(
            "id" => $row['GAME_ID'], // 加入 GAME_ID
            "image" => $row['GAME_COVER'],
            "title" => $row['GAME_NAME']
        );
        $jsonArray[] = $item;
    }
}
// -----------在這上面做資料處理回傳你想要的格式-----------------

// 關閉資料庫連線
// unset($pdo); 

// 轉換成 JSON 格式輸出 
echo json_encode($jsonArray);
?>
