<?php
// 包含 Conn.php 文件
include("Conn.php");

// 設定CORS標頭，允許 'content-type' 標頭欄位。
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

// 取得前端傳遞的 MEMBER_ID 參數
$memberID = $_POST['member_ID'];

// 假設您的資料庫連線已建立，並且適當地選擇了資料庫

// 執行查詢操作，篩選 MEMBER_ID 等於 $memberID 的記錄
$sql = "SELECT * 
FROM GAME_LIBRARY 
INNER JOIN GAME_DATA ON GAME_LIBRARY.GAME_ID = GAME_DATA.GAME_ID
WHERE MEMBER_ID = ?";
$statement = $pdo->prepare($sql);
$statement->execute([$memberID]);

$gameData = $statement->fetchAll(PDO::FETCH_ASSOC);

// 將會員資料以 JSON 格式返回給Vue組件
echo json_encode($gameData);
?>
