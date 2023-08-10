<?php
include("Conn.php"); // 資料庫連線

// 取得從前端傳遞的查詢類型和會員編號
$queryType = $_POST['queryType'];
$membership_number = $_POST['membership_number'];

// 建立 SQL 查詢
$sql = "SELECT * FROM MEMBER_DATA WHERE ";
if ($queryType === 'membership_number') {
    $sql .= "MEMBERSHIP_NUMBER = :membership_number";
} else if ($queryType === 'phone') {
    $sql .= "PHONE = :membership_number";
}

// 執行查詢
$statement = $pdo->prepare($sql);
$statement->bindValue(':membership_number', $membership_number);
$statement->execute();

// 取得查詢結果
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

// 將結果轉換為 JSON 格式輸出
echo json_encode($results);

// 關閉資料庫連線
unset($pdo);
?>
