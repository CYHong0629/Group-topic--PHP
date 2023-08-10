<?php

include("Conn.php");

header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $memberId = $_POST['memberId'];
  $newPassword = $_POST['newPassword'];

  // 更新數據庫中的密碼和MEMBER_STATUS
  $sql = "UPDATE MEMBER_DATA SET MEMBER_PASSWORD = :newPassword, MEMBER_STATUS = 1 WHERE MEMBER_ID = :memberId";
  echo $sql;
  $statement = $pdo->prepare($sql);
  $statement->bindParam(':newPassword', $newPassword);
  $statement->bindParam(':memberId', $memberId);
  
  if ($statement->execute()) {
    // 更新成功
    echo json_encode(['success' => true]);
  } else {
    // 更新失败
    echo json_encode(['success' => false, 'error' => 'Failed to update password']);
  }

  // 關閉數據庫連接
  unset($pdo);
}

?>
