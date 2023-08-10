<?php
// 此檔為範例，請依據個人需求做更改

// 資料庫連線(這行一定要寫!!!!!!!!!)
include("Conn.php");

header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 接收表單資料
    $couponCode = $_POST['couponCode'];
    $couponName = $_POST['couponName'];
    $couponDetail = $_POST['couponDetail'];
    $couponBegin = $_POST['couponBegin'];
    $couponEnd = $_POST['couponEnd'];
    $couponDiscount = $_POST['couponDiscount'];
    $minimumLimit = $_POST['minimumLimit'];
    $couponStatus = $_POST['couponStatus']; 

    if (empty($couponStatus)) {
        $couponStatus = 1;
    }
    // if (empty($couponEnd)) {
    //     $couponEnd = date('Y-m-d 23:59:59');
    // }
    // 撰寫 SQL 插入語句
    $sql = "INSERT INTO COUPON (COUPON_CODE, COUPON_NAME, COUPON_DETAIL, COUPON_BEGIN, COUPON_END, COUPON_DISCOUNT, MINIMUM_LIMIT , COUPON_STATUS) VALUES (?, ?, ?, ?, ?, ?, ? , ?)";

    // 執行插入語句
    $statement = $pdo->prepare($sql);
    $statement->execute([$couponCode, $couponName, $couponDetail, $couponBegin, $couponEnd, $couponDiscount, $minimumLimit, $couponStatus]);

    // 關閉資料庫連線
    unset($pdo);

    // 重定向到某個頁面
    // header("Location: index.php");
    // exit();
}
?>