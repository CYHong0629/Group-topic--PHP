<?php
// 包含 Conn.php 文件
include("Conn.php");

// 設定CORS標頭，允許 'content-type' 標頭欄位。
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

$connection = mysqli_connect($db_host, $db_user, $db_pass, $db_select);

// 獲取 POST 資料
$memberID = $_POST['member_ID'];
$price = intval($_POST['price']);
$selectCouponID = intval($_POST['select_coupon_id']);
$total = intval($_POST['total']);
$discount_price = intval($_POST['discount_price']);
$cartItems = json_decode($_POST['cartItems'], true);


// 執行 ORDER_DATA 欄位的寫入操作
$orderCode = date('YmdHis'); // 生成訂單代碼，格式為日期和時間
$orderDate = date('Y-m-d'); // 獲取當天日期

// 計算 DETAIL_SUM 的值
// $discount_price = $price - $total;

// 構建 ORDER_DATA SQL 插入語句
if ($selectCouponID != 0) {
    // 有選擇優惠券
    $sqlOrderData = "INSERT INTO ORDER_DATA (ORDER_CODE, MEMBER_ID, DETAIL_SUM, COUPON_ID, ORDER_DATE, SUM_PRICE) VALUES ('$orderCode', '$memberID', $discount_price, $selectCouponID, '$orderDate', $total)";
} else {
    // 未選擇優惠券
    $sqlOrderData = "INSERT INTO ORDER_DATA (ORDER_CODE, MEMBER_ID, DETAIL_SUM, ORDER_DATE, SUM_PRICE) VALUES ('$orderCode', '$memberID', $discount_price, '$orderDate', $total)";
}

// 執行 ORDER_DATA SQL 插入操作
$resultOrderData = mysqli_query($connection, $sqlOrderData);

// 獲取插入的 ORDER_ID
$orderID = mysqli_insert_id($connection);

// 執行 ORDER_DETAIL 欄位的寫入操作
foreach ($cartItems as $cartItem) {
    $gameID = $cartItem['ID'];
    $discountPercentage = floatval($cartItem['item_discounts_or']);
    $afterDiscountPrice = intval($cartItem['price']);

    // 構建 ORDER_DETAIL SQL 插入語句
    $sqlOrderDetail = "INSERT INTO ORDER_DETAIL (ORDER_ID, GAME_ID, DISCOUNT_PERCENTAGE, AFTER_DISCOUNT_PRICE) VALUES ($orderID, $gameID, $discountPercentage, $afterDiscountPrice)";

    // 執行 ORDER_DETAIL SQL 插入操作
    mysqli_query($connection, $sqlOrderDetail);
}

// 執行 GAME_LIBRARY 欄位的寫入操作
foreach ($cartItems as $cartItem) {
    $gameID = $cartItem['ID'];
    $memberID = $memberID;
    $gameTime = 0;
    $lastPlayed = null;
    $achievement = 0;

    // 構建 GAME_LIBRARY SQL 插入語句
    $sqlGameLibrary = "INSERT INTO GAME_LIBRARY (MEMBER_ID, GAME_ID, GAME_TIME, LAST_PLAYED, ACHIEVEMENT) VALUES ($memberID, $gameID, 0, NULL, 0)";

    // 執行 GAME_LIBRARY SQL 插入操作
    mysqli_query($connection, $sqlGameLibrary);
}


// 檢查寫入結果
if ($resultOrderData && mysqli_affected_rows($connection) > 0) {
    // 寫入成功
    // 返回成功訊息或其他操作
    echo "0";
} else {
    // 寫入失敗
    // 返回失敗訊息或其他操作
    echo "Failed to save order.";
}

// 關閉資料庫連接
mysqli_close($connection);
?>
