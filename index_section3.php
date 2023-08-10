<?php

// 此檔為範例，請依據個人需求做更改
//資料庫連線(這行一定要寫!!!!!!!!!)
include("Conn.php");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// 撰寫 SQL 查詢
// $sql = "SELECT * FROM 你要撈的資料表";
$sql = "SELECT
GAME_DATA.GAME_ID,
GAME_DATA.GAME_NAME,
GAME_DATA.GAME_COVER,
GAME_DATA.ORIGINAL_PRICE,
GAME_DATA.PURCHASED,
G_A_RELATION.ACTIVITY_ID,
ACTIVITY.ACTIVITY_NAME,
ACTIVITY.DISCOUNT_PERCENTAGE
FROM
G_A_RELATION
JOIN
GAME_DATA ON GAME_DATA.GAME_ID = G_A_RELATION.GAME_ID
JOIN
ACTIVITY ON G_A_RELATION.ACTIVITY_ID = ACTIVITY.ACTIVITY_ID
ORDER BY
RAND();";


// 執行查詢
$statement = $pdo->prepare($sql);
$statement->execute();

//抓出全部且依照順序封裝成一個二維陣列
$testResult  = $statement->fetchAll(PDO::FETCH_ASSOC);

// 建立最終的 JSON 格式陣列
$jsonArray = array();

// -----------在這下面做資料處理回傳你想要的格式-----------------

if (count($testResult) > 0) {
    foreach($testResult as $index => $testRow){
        $discountPercentage = (1 - $testRow['DISCOUNT_PERCENTAGE']) * 100;
        $price1 = round($testRow['ORIGINAL_PRICE'] * $testRow['DISCOUNT_PERCENTAGE']);
        $product = array(
            'id' => $testRow['GAME_ID'],
            'discount' => $discountPercentage,
            'title' => $testRow['GAME_NAME'],
            'image' => $testRow['GAME_COVER'],
            'price' => $price1,
            'price2' => $testRow['ORIGINAL_PRICE']
        );
        $jsonArray[] = $product;
    }
}

// -----------在這上面做資料處理回傳你想要的格式-----------------

// 關閉資料庫連線
// unset($pdo); 

// 轉換成 JSON 格式輸出 
echo json_encode($jsonArray);
?>
