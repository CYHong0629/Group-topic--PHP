<?php
    // 包含 Conn.php 文件
    include("Conn.php");
    
    
    // 設定CORS標頭，允許 'content-type' 標頭欄位。
     header("Access-Control-Allow-Methods: GET, POST");
     header("Access-Control-Allow-Headers: Content-Type");
     header("Access-Control-Allow-Credentials: true");

     $member_ID = $_POST['member_ID'];
    
     $sql = "SELECT *
            FROM MY_COUPON
            JOIN COUPON ON MY_COUPON.COUPON_ID = COUPON.COUPON_ID
            WHERE MY_COUPON.MEMBER_ID = :member_id;";

        $statement = $pdo->prepare($sql);
        $statement->bindParam(':member_id', $member_ID);
        $affectedRow = $statement->execute();
        $coupons = $statement->fetchAll(PDO::FETCH_ASSOC);

      
      header('Content-Type: application/json');
      echo json_encode($coupons);
?>