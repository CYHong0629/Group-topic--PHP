<?php


include("Conn.php");

if (isset($_GET['id'])) {
  // 獲取URL中的ID參數
  $couponId = $_GET['id'];

  // 撰寫 SQL 查詢，根據ID篩選數據
  $sql = "SELECT * FROM COUPON WHERE COUPON_ID = :couponId";

  // 執行查詢
  $statement = $pdo->prepare($sql);
  $statement->bindParam(':couponId', $couponId);
  $statement->execute();

  // 獲取單個的詳細信息
  $coupon = $statement->fetch(PDO::FETCH_ASSOC);

  // 關閉數據庫連接
  unset($pdo);

  // 輸出詳細信息
  echo json_encode($coupon);
} else {
  // 撰寫 SQL 查詢，獲取所有數據
  $sql = "SELECT * FROM COUPON";

  // 執行查詢
  $statement = $pdo->prepare($sql);
  $statement->execute();

  // 抓出全部且封裝成一個二維數組
  $coupons = $statement->fetchAll(PDO::FETCH_ASSOC);

  // 關閉數據庫連接
  unset($pdo);

  // 輸出所有數據
  echo json_encode($coupons);
}
