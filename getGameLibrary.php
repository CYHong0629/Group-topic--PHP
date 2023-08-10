<?php
// 包含 Conn.php 文件
include("Conn.php");

// 設定CORS標頭，允許 'content-type' 標頭欄位。
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

$connection = mysqli_connect($db_host, $db_user, $db_pass, $db_select);

// 取得前端傳遞的 MEMBER_ID 參數
$memberID = $_POST['member_ID'];

// 假設您的資料庫連線已建立，並且適當地選擇了資料庫

// 執行查詢操作，篩選 MEMBER_ID 等於 $memberID 的記錄
$query = "SELECT GAME_ID FROM GAME_LIBRARY WHERE MEMBER_ID = '$memberID'";
$result = mysqli_query($connection, $query);

// 儲存 GAME_ID 的陣列
$gameIDs = array();

// 檢查是否有符合條件的記錄
if (mysqli_num_rows($result) > 0) {
  // 迭代結果集，將每個 GAME_ID 存入陣列
  while ($row = mysqli_fetch_assoc($result)) {
    $gameIDs[] = $row['GAME_ID'];
  }
}

// 將陣列轉換為 JSON 格式回傳
echo json_encode($gameIDs);


?>