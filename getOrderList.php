<?php
include("Conn.php");

header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

$connection = mysqli_connect($db_host, $db_user, $db_pass, $db_select);

$memberID = $_POST['member_ID'];

$query = "SELECT ORDER_DATA.ORDER_CODE, ORDER_DATA.ORDER_DATE, ORDER_DATA.SUM_PRICE, GAME_DATA.GAME_COVER, ORDER_DETAIL.DISCOUNT_PERCENTAGE,  ORDER_DETAIL.AFTER_DISCOUNT_PRICE, GAME_DATA.GAME_NAME, ORDER_DATA.DETAIL_SUM, COUPON.COUPON_NAME
          FROM ORDER_DATA 
          INNER JOIN ORDER_DETAIL ON ORDER_DATA.ORDER_ID = ORDER_DETAIL.ORDER_ID 
          INNER JOIN GAME_DATA ON ORDER_DETAIL.GAME_ID = GAME_DATA.GAME_ID 
          LEFT JOIN COUPON ON ORDER_DATA.COUPON_ID = COUPON.COUPON_ID
          WHERE ORDER_DATA.MEMBER_ID = '$memberID'";

$result = mysqli_query($connection, $query);

$orders = array();

while ($row = mysqli_fetch_assoc($result)) {
    $orderId = $row['ORDER_CODE'];

    // 檢查是否已存在該訂單編號的訂單
    $existingOrder = array_filter($orders, function ($order) use ($orderId) {
        return $order['orderId'] === $orderId;
    });

    if (empty($existingOrder)) {
        // 該訂單編號的訂單尚未存在，創建一個新的訂單
        $order = array(
            'orderId' => $orderId,
            'purchaseDate' => $row['ORDER_DATE'],
            'purchaseAmount' => $row['SUM_PRICE'],
            'orderStatus' => '已完成',
            'showDetails' => false,
            'items' => array(),
            'coupon' => $row['COUPON_NAME'],
            'discount' => $row['DETAIL_SUM'],
            'totalAmount' => $row['SUM_PRICE']
        );

        $orders[] = $order;
    }

    // 將訂單明細添加到對應的訂單中
    $item = array(
        'itemId' => '',
        'name' => $row['GAME_NAME'],
        'image' => $row['GAME_COVER'],
        'discount' => $row['DISCOUNT_PERCENTAGE'],
        'price' => $row['AFTER_DISCOUNT_PRICE'],
        'originalPrice' => $row['AFTER_DISCOUNT_PRICE']
    );

    $index = array_search($orderId, array_column($orders, 'orderId'));
    $orders[$index]['items'][] = $item;
}

echo json_encode($orders);

?>
