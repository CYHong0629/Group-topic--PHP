<?php
// 包含 Conn.php 文件
include("Conn.php");


// 設定CORS標頭，允許 'content-type' 標頭欄位。
 header("Access-Control-Allow-Methods: GET, POST");
 header("Access-Control-Allow-Headers: Content-Type");
 header("Access-Control-Allow-Credentials: true");

  // 獲取從表單提交的資料
  $name = $_POST['name'];
  $nickname = $_POST['nickname'];
  $phone = $_POST['phone'];
  $gender = $_POST['gender'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $date = DateTime::createFromFormat('Y-m-d', $_POST['dateOfBirth']);
  $formattedDate = $date->format('Y-m-d');

  // 在這裡進行資料驗證、處理和寫入資料庫的相關程式碼
  // 請注意安全性，如資料驗證、避免 SQL 注入等

  // 範例：將資料寫入資料庫
  // 使用 Conn.php 文件中的 $pdo 物件

date_default_timezone_set("Asia/Taipei"); 
  $sql = "INSERT INTO MEMBER_DATA (MEMBERSHIP_NUMBER, NICKNAME, USERNAME, GENDER, MEMBER_ACCOUNT, MEMBER_PASSWORD, PHONE, MEMBER_PHOTO, BIRTHDAY, REGISTRATION_DATE, MEMBER_STATUS)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


  $statement = $pdo->prepare($sql);
  $statement->bindValue(1, date("YmdHis"));
  $statement->bindValue(2, $nickname);
  $statement->bindValue(3, $name);
  $statement->bindValue(4, $gender);
  $statement->bindValue(5, $email);
  $statement->bindValue(6, $password);
  $statement->bindValue(7, $phone);
  $statement->bindValue(8, 'pic/img/member_photo/default.png');
  $statement->bindValue(9, $formattedDate);
  $statement->bindValue(10, date("Y-m-d"));
  $statement->bindValue(11, 0 );

  $affectedRow = $statement->execute();

  if ($affectedRow > 0) {
    echo '註冊成功';
  } else {
    echo '發生錯誤：' . $statement->errorInfo()[2];
  }

?>
