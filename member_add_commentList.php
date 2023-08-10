<?php
// 包含 Conn.php 文件
include("Conn.php");

// 設定CORS標頭，允許 'content-type' 標頭欄位。
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

// 獲取從Vue組件傳遞的帳號參數
$member_ID = $_POST['member_ID'];
$rating = $_POST['rating'];
$content = $_POST['content'];
$gameId = $_POST['gameId'];

// 在這裡進行資料驗證、處理和從資料庫獲取會員資料的相關程式碼
// 請注意安全性，如資料驗證、避免 SQL 注入等

// 範例：從資料庫獲取會員資料
// 使用 Conn.php 文件中的 $pdo 物件
$sql = "INSERT INTO COMMENT_LIST (MEMBER_ID, GAME_ID, COMMENT_DATE, COMMENT_CONTENT, STAR_RATING, COMMENT_LIKES) 
VALUES (?, ?, ?, ?, ?, ?)";
$statement = $pdo->prepare($sql);
$statement->bindValue(1, $member_ID);
$statement->bindValue(2, $gameId);
$statement->bindValue(3, date("Y-m-d"));
$statement->bindValue(4, $content);
$statement->bindValue(5, $rating);
$statement->bindValue(6, 0);

$affectedRow = $statement->execute();

if ($affectedRow > 0) {
  echo '註冊成功';
} else {
  echo '發生錯誤：' . $statement->errorInfo()[2];
}

?>
