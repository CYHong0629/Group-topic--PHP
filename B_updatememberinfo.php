<?php

include("Conn.php");

header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $memberId = $_POST['memberId'];
    $newNickname = $_POST['newNickname'];
    $newPhone = $_POST['newPhone'];
  
    // 更新數據庫中的暱稱和電話
    $sql = "UPDATE MEMBER_DATA SET NICKNAME = :newNickname, PHONE = :newPhone WHERE MEMBER_ID = :memberId";
  
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':newNickname', $newNickname);
    $statement->bindParam(':newPhone', $newPhone);
    $statement->bindParam(':memberId', $memberId);
    
    
    if ($statement->execute()) {
      // 更新成功
      echo json_encode(['success' => true]);
    } else {
      // 更新失败
      echo json_encode(['success' => false, 'error' => 'Failed to update information']);
    }
  
    // 關閉數據庫連接
    unset($pdo);
  }
  

?>
