<?php
// 資料庫連線(這行一定要寫!!!!!!!!!)
include("Conn.php");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// 撰寫 SQL 查詢
$sql = "SELECT 
ACTIVITY_ID, 
ACTIVITY_BEGIN, 
ACTIVITY_END, 
DISCOUNT_PERCENTAGE,
ACTIVITY_IMG
FROM ACTIVITY";

// 執行查詢
$statement = $pdo->prepare($sql);
$statement->execute();

// 抓出全部且依照順序封裝成一個二維陣列
$testResult = $statement->fetchAll(PDO::FETCH_ASSOC);

// 建立最終的 JSON 格式陣列
$jsonArray = array();

// 在這下面做資料處理回傳你想要的格式

if (count($testResult) > 0) {
    foreach ($testResult as $index => $testRow) {
      $discountPercentage = round((1 - $testRow['DISCOUNT_PERCENTAGE']) * 100);
  
      $item = array(
        'id' => $testRow['ACTIVITY_ID'],
        'img' => $testRow['ACTIVITY_IMG'],
        'preferential' => $discountPercentage,
        'time' => calculateTimeRemaining($testRow['ACTIVITY_BEGIN'], $testRow['ACTIVITY_END'])
      );
  
      $jsonArray[] = $item;
    }
  }
  

// 關閉資料庫連線
// unset($pdo);

// 轉換成 JSON 格式輸出
echo json_encode($jsonArray);

// 計算剩餘時間的函式
function calculateTimeRemaining($beginTime, $endTime) {
  date_default_timezone_set('Asia/Taipei'); // 設定時區，請根據您的實際需求進行更改

  $currentTime = time(); // 現在的時間戳記
  $endTime = strtotime($endTime); // 結束時間的時間戳記

  $timeRemaining = $endTime - $currentTime; // 剩餘時間（秒）

  $days = floor($timeRemaining / (24 * 60 * 60)); // 剩餘天數
  $hours = floor(($timeRemaining % (24 * 60 * 60)) / (60 * 60)); // 剩餘小時數
  $minutes = floor(($timeRemaining % (60 * 60)) / 60); // 剩餘分鐘數
  // $seconds = $timeRemaining % 60; // 剩餘秒數

  $timeString = sprintf('剩餘%d天%d小時%d分鐘', $days, $hours, $minutes);

  return $timeString;
}
?>