<?php
include("Conn.php"); // 資料庫連線

// 取得從前端傳遞的查詢類型和會員編號
$queryType = $_POST['queryType'];
$whichgame = $_POST['whichgame'];

// 建立 SQL 查詢
$sql = "SELECT * FROM GAME_DATA WHERE ";
if ($queryType === 'gamesid') {
    $sql .= "GAME_ID = :whichgame";
} else if ($queryType === 'gamesname') {
    $sql .= "GAME_NAME LIKE CONCAT('%', :whichgame, '%')";
}

// 執行查詢
$statement = $pdo->prepare($sql);
$statement->bindValue(':whichgame', $whichgame);
$statement->execute();

// 取得查詢結果
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

// 將結果轉換為 JSON 格式輸出
echo json_encode($results);

// 關閉資料庫連線
unset($pdo);
?>
