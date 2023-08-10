<?php

include("Conn.php");

if (isset($_GET['id'])) {
  // 獲取URL中的會員ID參數
  $orderId = $_GET['id'];

  $sql = "SELECT OD.ORDER_ID, OD.ORDER_CODE, OD.MEMBER_ID, MD.MEMBERSHIP_NUMBER, MD.NICKNAME, MD.USERNAME, MD.GENDER, MD.MEMBER_ACCOUNT, MD.MEMBER_PASSWORD, MD.PHONE, MD.MEMBER_PHOTO, MD.BIRTHDAY, MD.REGISTRATION_DATE, MD.MEMBER_STATUS, OD.DETAIL_SUM, OD.COUPON_ID, CP.COUPON_CODE, CP.COUPON_NAME, CP.COUPON_DETAIL, CP.COUPON_BEGIN, CP.COUPON_END, CP.COUPON_DISCOUNT, CP.MINIMUM_LIMIT, CP.COUPON_STATUS, OD.ORDER_DATE, OD.SUM_PRICE, ODT.GAME_ID, GD.GAME_NAME, GD.GAME_COVER, GD.GAME_INTRO, GD.ORIGINAL_PRICE, GD.GAME_STATUS, GD.RELEASE_DATE, GD.PUBLISHER, GD.DEVELOPER, GD.RATING_ID, GD.SYSTEM_REQUIREMENT, GD.TOTAL_COMMENTS, ODT.DISCOUNT_PERCENTAGE, ODT.AFTER_DISCOUNT_PRICE
  FROM ORDER_DATA OD
  JOIN MEMBER_DATA MD ON OD.MEMBER_ID = MD.MEMBER_ID
  JOIN COUPON CP ON OD.COUPON_ID = CP.COUPON_ID
  JOIN ORDER_DETAIL ODT ON OD.ORDER_ID = ODT.ORDER_ID
  JOIN GAME_DATA GD ON ODT.GAME_ID = GD.GAME_ID
  WHERE OD.ORDER_ID = :orderId";

  //
  $gamesql = "SELECT ORDER_ID, GAME_COVER, GAME_NAME, DISCOUNT_PERCENTAGE, ORIGINAL_PRICE, AFTER_DISCOUNT_PRICE
  FROM ORDER_DETAIL
  JOIN GAME_DATA ON ORDER_DETAIL.GAME_ID = GAME_DATA.GAME_ID
  WHERE ORDER_DETAIL.ORDER_ID = :orderId";
  $gamestatement = $pdo->prepare($gamesql);
  $gamestatement->bindParam(':orderId', $orderId);
  $gamestatement->execute();

  $gamedata = $gamestatement->fetchAll(PDO::FETCH_ASSOC);
  // $game['GAME'] = $gamedata;
  //

  // 執行查詢
  $statement = $pdo->prepare($sql);
  $statement->bindParam(':orderId', $orderId);
  $statement->execute();

  // 獲取單個會員的詳細信息
  $order = $statement->fetch(PDO::FETCH_ASSOC);

  // 進行商品折扣計算
  $originalPrice = $order['ORIGINAL_PRICE'];
  $discountPercentage = $order['DISCOUNT_PERCENTAGE'];
  $couponDiscount = $order['COUPON_DISCOUNT'];

  // 根據折扣條件判斷是否應用折扣
  if ($originalPrice > 1000) {
    // 根據折扣計算折扣後的價格
    $afterDiscountPrice = $originalPrice - $discountPercentage;
  } else {
    // 不滿足折扣條件時，折扣後的價格與原價格相同
    $afterDiscountPrice = $originalPrice;
  }

  // 將折扣後的價格保存到訂單數據中
  $order['AFTER_DISCOUNT_PRICE'] = $afterDiscountPrice;

  // 計算最終價格（折扣後價格減去優惠券折扣）
  $order['SUM_PRICE'] = $afterDiscountPrice - $couponDiscount;

  // 關閉數據庫連接
  unset($pdo);

  // 創建包含完整訂單信息和遊戲信息的關聯數組
  $data = array(
    'order' => $order,
    'game' => $gamedata
  );

  // 輸出包含兩個信息的關聯數組
  echo json_encode($data);
} else {
  // 撰寫 SQL 查詢，獲取所有會員數據
  $sql = "SELECT OD.ORDER_ID, OD.ORDER_CODE, MD.MEMBERSHIP_NUMBER, MD.MEMBER_ACCOUNT, OD.ORDER_DATE, SUM(OD.SUM_PRICE) AS TOTAL_SUM_PRICE
  FROM ORDER_DATA OD
  JOIN MEMBER_DATA MD ON OD.MEMBER_ID = MD.MEMBER_ID
  GROUP BY OD.ORDER_ID, OD.ORDER_CODE, MD.MEMBERSHIP_NUMBER, MD.MEMBER_ACCOUNT, OD.ORDER_DATE";

  // 執行查詢
  $statement = $pdo->prepare($sql);
  $statement->execute();

  // 抓出全部且封裝成一個二維數組
  $orders = $statement->fetchAll(PDO::FETCH_ASSOC);

  // 進行商品折扣計算
  foreach ($orders as &$order) {
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

  // 關閉數據庫連接
  unset($pdo);

  // 輸出所有訂單數據
  echo json_encode($orders);
}
