<?php
// 包含 Conn.php 文件
include("Conn.php");

// 設定CORS標頭，允許 'content-type' 標頭欄位。
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

// 獲取從Vue組件傳遞的帳號參數
$member_ID = $_POST['member_ID'];
$gameId = $_POST['gameId'];

// 在這裡進行資料驗證、處理和從資料庫獲取會員資料的相關程式碼
// 請注意安全性，如資料驗證、避免 SQL 注入等


$sql = "SELECT COUNT(*) FROM COMMENT_LIST WHERE MEMBER_ID = ? AND GAME_ID = ?";
$statement = $pdo->prepare($sql);
$statement->bindValue(1, $member_ID);
$statement->bindValue(2, $gameId);
$statement->execute();

$count = $statement->fetchColumn();

// 根據結果返回驗證結果
$response = array('match' => $count > 0);
echo json_encode($response);
?>
