<?php
include("Conn.php"); // 資料庫連線

// 取得從前端傳遞的查詢類型和會員編號
$queryType = $_POST['queryType'];
$member_account = $_POST['member_account'];

// 建立 SQL 查詢
$sql = "SELECT OD.ORDER_ID, OD.ORDER_CODE, MD.MEMBERSHIP_NUMBER, MD.MEMBER_ACCOUNT, OD.ORDER_DATE, SUM(OD.SUM_PRICE) AS TOTAL_SUM_PRICE
FROM ORDER_DATA OD
JOIN MEMBER_DATA MD ON OD.MEMBER_ID = MD.MEMBER_ID";

if ($queryType === 'member_account') {
    $sql .= " WHERE MD.MEMBER_ACCOUNT LIKE CONCAT('%', :member_account, '%')";
} else if ($queryType === 'order_code') {
    $sql .= " WHERE OD.ORDER_CODE = :member_account";
}

$sql .= " GROUP BY OD.ORDER_ID, OD.ORDER_CODE, MD.MEMBERSHIP_NUMBER, MD.MEMBER_ACCOUNT, OD.ORDER_DATE";


// 執行查詢
$statement = $pdo->prepare($sql);
$statement->bindValue(':member_account', $member_account);
$statement->execute();

// $statement = $pdo->prepare($ordersql);
// $statement->bindValue(':member_account', $member_account);
// $statement->execute();

// 取得查詢結果
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

  // 进行商品折扣计算
  foreach ($results as &$order) {
    $originalPrice = $order['TOTAL_SUM_PRICE'];
    $discountPercentage = 500; // 折扣金額

    // 根據折扣計算折扣後的價格
    if ($originalPrice > 1000) {
      $afterDiscountPrice = $originalPrice - $discountPercentage;
    } else {
      $afterDiscountPrice = $originalPrice;
    }

    // 將折扣後的價格保存到訂單數據中
    $order['AFTER_DISCOUNT_PRICE'] = $afterDiscountPrice;
  }

// 將結果轉換為 JSON 格式輸出
echo json_encode($results);

// 關閉資料庫連線
unset($pdo);
?>
