<?php
// 包含 Conn.php 文件
include("Conn.php");

// 設定CORS標頭，允許 'content-type' 標頭欄位。
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

// 獲取從Vue組件傳遞的帳號參數
$account = $_POST['email'];

// 在這裡進行資料驗證、處理和從資料庫獲取會員資料的相關程式碼
// 請注意安全性，如資料驗證、避免 SQL 注入等

// 範例：從資料庫獲取會員資料
// 使用 Conn.php 文件中的 $pdo 物件
$sql = "SELECT * FROM MEMBER_DATA WHERE MEMBER_ACCOUNT = ?";
$statement = $pdo->prepare($sql);
$statement->bindValue(1, $account);
$statement->execute();

$memberData = $statement->fetch(PDO::FETCH_ASSOC);

// 將會員資料以 JSON 格式返回給Vue組件
echo json_encode($memberData);
?>
