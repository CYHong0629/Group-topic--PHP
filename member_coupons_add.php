<?php
    // 包含 Conn.php 文件
    include("Conn.php");
    
    
    // 設定CORS標頭，允許 'content-type' 標頭欄位。
     header("Access-Control-Allow-Methods: GET, POST");
     header("Access-Control-Allow-Headers: Content-Type");
     header("Access-Control-Allow-Credentials: true");

     $couponCode = $_POST['couponCode'];
     $member_ID = $_POST['member_ID'];
    
     $sql = "SELECT *
            FROM COUPON
            WHERE COUPON_CODE = ?";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(1, $couponCode);
        $affectedRow = $statement->execute();
        $coupon = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        if ($affectedRow > 0 && $coupon != null) {
          $coupon_ID = $coupon[0]['COUPON_ID'];
          
            $sql = "INSERT INTO MY_COUPON (MEMBER_ID, COUPON_ID, MY_COUPON_STATUS) VALUES (?, ?, ?)";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(1, $member_ID);
            $statement->bindParam(2, $coupon_ID);
            $statement->bindValue(3, '1');
            $affectedRows = $statement->execute();
          
            if ($affectedRows) {
              // 成功將優惠券寫入 MY_COUPON 資料表中
              $response = array('success' => true, 'message' => '優惠券已成功應用');
              echo json_encode($response);
            } else {
              // 寫入 MY_COUPON 資料表時出現錯誤
              $response = array('success' => false, 'message' => '寫入 MY_COUPON 資料表時發生錯誤');
              echo json_encode($response);
            }
        } else {
            // 優惠券代碼不存在於 COUPON 資料表中
            $response = array('success' => false, 'message' => '優惠券代碼不存在');
            echo json_encode($response);
          }
          ?>